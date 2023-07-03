<?php
/** Gestion de l'interface pour modifier une page sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurModpage{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		//
		if(!isset($_POST['url']))
			return fctErrorMSG('url innexistante');
		
		//Séparer le site de la page (dans l'URL)
		preg_match('/^([^\/]+)(?:[\/]([a-z0-9]+))?(?:[?](.+))?/', $_POST['url'], $matches);
		if (count($matches)<=3)
			return fctErrorMSG('url invalide');
		
		$url_site = $matches[1];
		$url_page = $matches[2];
		$url_param= $matches[3]; //Id de la page a modifier
		
		
		//Valider si la personne a le droit de modifier la page
		$query = 'SELECT a.modifier, a.admin, p.*, pa.id as paid
					FROM ' . DB_PREFIX . 'sitesweb_pages as p
					LEFT JOIN ' . DB_PREFIX . 'sitesweb_acces as a ON (a.site_id=p.site_id)
					LEFT JOIN ' . DB_PREFIX . 'sitesweb_pages_acces as pa ON (pa.page_id = p.id AND user_id = a.id)
					WHERE	p.id = ' . $url_param . '
						AND a.user="' . addslashes($_POST['user']) . '"
						AND a.pass="' . addslashes($_POST['pass']) . '"
					LIMIT 1;';
		$result = $db->query($query,__FILE__,__LINE__);
		if(mysql_num_rows($result)==0)
			return fctErrorMSG('acces invalide (1)');
		
		$arr= mysql_fetch_assoc($result);
		if($arr['modifier']=='0' && $arr['admin']=='0')
			return fctErrorMSG('acces invalide (2)');
		
		if($arr['admin']=='0' && $arr['acces']=='priv' && empty($arr['paid']))
			return fctErrorMSG('acces invalide (3)');
		
		
		
		
		//Charger la page à modifer
		$page = new Member_SitewebPage($arr);
		$tpl->set('PAGE', $page);
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetModpage.htm',__FILE__,__LINE__);
	}
}
?>