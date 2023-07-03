<?php
/** Gestion de l'action de jeter un item. Cette page est utilisée UNIQUEMENT par AJAX. des # d'erreur sont retourné, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_Sitepagedel{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		$actionPa = 3;
		//$cout_ouverture = 5;
		
		
		
		//TODO: Valider si le lieu donne accès à l'ordinateur + Internet
		
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			die('01|' . rawurlencode('Votre n\'êtes pas en état d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die('02|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		if(!preg_match('/^([^\/]+)(?:[\/]([0-9]+))?(?:[?](.+))?/', $_POST['url'], $matches))
			die('03|' . rawurlencode('L\'URL du site est invalide.'));
		
		if(count($matches)<=2)
			die('04|' . rawurlencode('L\'id du site est manquant ou invalide.'));
		
		
		//Vérifier si l'URL existe 
		$site = Member_Siteweb::loadSite ($db, $matches[1]);
		if (!$site)
			die('10|' . rawurlencode('Cette URL n\'existe pas.'));
		
		//Vérifier si l'accès est valide
		$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if(!$acces)
			die('11|' . rawurlencode('Vous ne possèdez pas les autorisations nécésaires (1).'));
			
		if($acces['modifier'] !=1 && $acces['admin'] !=1)
			die('12|' . rawurlencode('Vous ne possèdez pas les autorisations nécésaires (2).'));
		
		//Vérifier que la page appartient au site
		$i=0; $e=0; $found = false;
		while( $page = $site->getPage($db, $i++))
			if($page->getId() == $matches[2])
				$found=true;
		if (!$found)
			die('12|' . rawurlencode('Cette page n\'appartiend pas à ce site.'));
		
		
		//Tout est ok, Effacer la page :(
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		
		$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_pages_acces
					WHERE page_id=' . $matches[2] . ';';
		$db->query($query,__FILE__,__LINE__);
		
		$query = 'DELETE FROM ' . DB_PREFIX . 'sitesweb_pages
					WHERE id=' . $matches[2] . ';';
		$db->query($query,__FILE__,__LINE__);
		
		
		
		
		
		die('OK|' . $perso->getPa()); //Tout est OK
	}
}
?>