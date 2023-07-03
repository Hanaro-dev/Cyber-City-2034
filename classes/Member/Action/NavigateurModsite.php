<?php
/** Gestion de l'interface pour créer un site sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurModsite{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		$actionPa = 3;
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			return fctErrorMsg('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		if($perso->getPa() < $actionPa)
			return fctErrorMsg('Vous n\'avez pas assez de PA pour effectuer cette action.');
		
		preg_match('/^([^\/]+)(?:[\/]([a-z0-9]+))?(?:[?](.+))?/', $_POST['url'], $matches);
		
		if(count($matches)<3)
			return fctErrorMsg('L\'URL du site est invalide (2).');
		
		$site_url = $matches[1];
		$mod_site_url = $matches[3];
		
		//Vérifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $mod_site_url);
		if (!$site)
			return fctErrorMsg('Cette URL n\'existe pas.');
		
		//Vérifier si l'accès est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			return fctErrorMsg('Vous ne possèdez pas les autorisations nécésaires (1).');
			
		if(!$acces->isAdmin())
			return fctErrorMsg('Vous ne possèdez pas les autorisations nécésaires (2).');
			
		
		
		//Trouver les pages du site
		$PAGES = array();
		$i=0; $e=0;
		while( $page = $site->getPage($tpl, $db, $session, $account, $perso, $i++))
			$PAGES[$e++] = $page;
		$tpl->set('PAGES', $PAGES);
		
		//Retourner le template complété/rempli
		$tpl->set('SITE', $site);
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetModsite.htm',__FILE__,__LINE__);
	}
}
?>