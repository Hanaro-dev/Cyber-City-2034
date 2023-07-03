<?php
/** Gestion de l'interface pour créer un site sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurModacces{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			return fctErrorMsg('Votre n\'êtes pas en état d\'effectuer cette action.');
		
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
			
		
		
		//En cas d'ajout, effectuer l'ajout du champ bidon AVANT
		if(isset($_POST['add'])){
			$query = 'INSERT INTO ' . DB_PREFIX . 'sitesweb_acces
						(`site_id`)
						VALUES
						(' . $site->getId() . ');';
			$db->query($query, __FILE__, __LINE__);
			$site->clearAcces();
		}
		
		if(isset($_POST['mod'])){
			$i=0;
			while( $ax = $site->getAcces($db, $i++)) {
				if(isset($_POST['ax_' . $ax->getId() . '_del'])){
					if ($acces->getId() == $ax->getId()){
						echo fctErrorMSG('Vous ne pouvez pas vous auto-supprimer.');
					}else{
						$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_acces
									WHERE	id=' . $ax->getId() . '
										AND site_id=' . $site->getId() . '
									LIMIT 1;';
						$db->query($query, __FILE__, __LINE__);
						
						$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_pages_acces
									WHERE	user_id=' . $ax->getId() . ';';
						$db->query($query, __FILE__, __LINE__);
					}
				}else{
					if(isset($_POST['ax_' . $ax->getId() . '_user'])){
						$query = 'UPDATE ' . DB_PREFIX . 'sitesweb_acces
									SET	`user`		="' . addslashes($_POST['ax_' . $ax->getId() . '_user']) . '",
										`pass`		="' . addslashes($_POST['ax_' . $ax->getId() . '_pass']) . '",
										`accede`	="' . (isset($_POST['ax_' . $ax->getId() . '_accede'])	? '1' : '0') . '",
										`poste`		="' . (isset($_POST['ax_' . $ax->getId() . '_poste'])	? '1' : '0') . '",
										`modifier`	="' . (isset($_POST['ax_' . $ax->getId() . '_modifier'])? '1' : '0') . '",
										`admin`		="' . (isset($_POST['ax_' . $ax->getId() . '_admin'])	? '1' : '0') . '"
									WHERE	id=' . $ax->getId() . '
										AND site_id=' . $site->getId() . '
									LIMIT 1;';
						$db->query($query, __FILE__, __LINE__);
					}
				}
				
			}
			$site->clearAcces();
		}
		
		
		//Trouver le accès déjà existants
		$i=0; $e=0; $arrAcces = array();
		while( $ax = $site->getAcces($db, $i++))
			$arrAcces[$e++] = $ax;
		$tpl->set('ACCES', $arrAcces);
		
		//Retourner le template complété/rempli
		$tpl->set('SITE', $site);
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetAcces.htm',__FILE__,__LINE__);
	}
}
?>