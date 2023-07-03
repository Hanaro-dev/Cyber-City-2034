<?php
/** Gestion de l'interface de l'action Parler: Afficher l'interface pour parler.
*
* @package Member_Action
*/
class Member_Action_Navigateur{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//## AFFICHER L'ENTÊTE DU NAVIGATEUR
		if (!isset($_POST['url']) || empty($_POST['url']))
			$_POST['url'] = "dom.net";
		
		$tpl->set('URL', $_POST['url']);
		$tpl->set('USER', ((isset($_POST['user'])) ? $_POST['user'] : ''));
		$tpl->set('PASS', ((isset($_POST['pass'])) ? $_POST['pass'] : ''));
		
		
		//## AFFICHER LE CONTENU DE LA PAGE
		
		
		
		//Séparer le site de la page (dans l'URL)
		preg_match('/^([^\/]+)(?:[\/]([a-z0-9]+))?(?:[?](.+))?/', $_POST['url'], $matches);
		
		//$tmp = explode('/',$_POST['url']);
		$url_site = null;
		$url_page = null;
		$url_param= null;
		
		if (count($matches)>1)	$url_site = $matches[1];
		if (count($matches)>2)	$url_page = $matches[2];
		if (count($matches)>3)	$url_param= $matches[3];
		
		
		//Charger le site
		$site = Member_Siteweb::loadSite($db, $url_site);
		$tpl->set('site', $site);
		$tpl->set('url_param', addslashes(((!empty($url_param)) ? $url_param : '')));
		
		//Charger l'entête (le menu de navigation)
		$header = $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurHeader.htm',__FILE__,__LINE__);
		$tpl->set('HEADER', $header);
		
		
		
		//Vérifier si le site est existant
		if(!$site)
			$erreur_site = $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurNotFound.htm',__FILE__,__LINE__);
		
		//Vérifier les droits d'accès au site
		$acces=false;
		if ($site && isset($_POST['user']) && isset($_POST['pass']))
			$acces = $site->checkAcces($db, $_POST['user'], $_POST['pass']);
		
		if($site && !$site->isPublic())
			if(!$acces)
				$erreur_site = $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurAccessDenied.htm',__FILE__,__LINE__);
		
		$tpl->set('SITE_ACCES', $acces);
		
		
		
		
		//Charger la page
		if (isset($erreur_site)){
			$page_source = $erreur_site;
		}else{
			if(is_numeric($url_page) || empty($url_page)){
				//Il s'agit d'une page régulière
				$page_source = self::loadPageSource($tpl,
											$db,
											$session,
											$account,
											$perso,
											$site,
											$url_page,
											((isset($_POST['user'])) ? $_POST['user'] : ''),
											((isset($_POST['pass'])) ? $_POST['pass'] : ''),
											$acces
										);
			}else{
				//Il s'agit d'une page système
				switch($url_page){
					case 'addsite':
						$page_source = Member_Action_NavigateurAddsite::generatePage($tpl, $db, $session, $account, $perso);
						break;
					case 'addpage':
						$page_source = Member_Action_NavigateurAddpage::generatePage($tpl, $db, $session, $account, $perso);
						break;
					case 'modpage':
						$page_source = Member_Action_NavigateurModpage::generatePage($tpl, $db, $session, $account, $perso);
						break;
					case 'modsite':
						$page_source = Member_Action_NavigateurModsite::generatePage($tpl, $db, $session, $account, $perso);
						break;
					case 'modacces':
						$page_source = Member_Action_NavigateurModacces::generatePage($tpl, $db, $session, $account, $perso);
						break;
					case 'modpageacces':
						$page_source = Member_Action_NavigateurModpageacces::generatePage($tpl, $db, $session, $account, $perso);
						break;
					default:
						$page_source = $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurNotFound.htm',__FILE__,__LINE__);
				}
			}
		}
		
		if(count($page_source)==2){
			$tpl->set('TITRE', $page_source[0]);
			$tpl->set('PAGE', $page_source[1]);
		}else{
			$tpl->set('PAGE', $page_source);
		}
		
		//Charger l'index
		if($site && ($site->isPublic() || $acces)){
			
			$i=0; $e=0; $arrPages = array();
			while( $page = $site->getPage($tpl, $db, $session, $account, $perso, $i++))
				if($page->getShowIndex())
					$arrPages[$e++] = $page;
			
			$tpl->set('arrPages', $arrPages);
			
			
			//Valider les accès
			if($acces && ($acces->canPoste() || $acces->isAdmin()))
				$tpl->set('canPost', true);
			if($acces && $acces->isAdmin())
				$tpl->set('admin', true);
			
			$page_source = $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurIndex.htm',__FILE__,__LINE__);
			$tpl->set('INDEX', $page_source);
		}
		
		
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateur.htm',__FILE__,__LINE__);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	private static function loadPageSource(&$tpl, &$db, &$session, &$account, &$perso, &$site, $url_page, $user, $pass, $acces){
		
		
		//Si aucune URL de page spécifiée, charger la page d'accueil
		if(empty($url_page)){
			$first_page = $site->getFirstPage($tpl, $db, $session, $account, $perso); //Charger la 'first page'
			if ($first_page)
				return $first_page->getContentHTML();
			else
				return ''; //Aucune page demandé + aucune page d'accueil
		}
		
		
		//Si une URL de page est spécifiée, charger la page (Valide si la page appartiend au site)
		$i=0; $found=false;
		while( $page = $site->getPage($tpl, $db, $session, $account, $perso, $i++)){
			if($page->getId() == $url_page){
				$found=true;
				break;
			}
		}
		if (!$found)
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurNotFound.htm',__FILE__,__LINE__);
		
		
		
		//Vérifier les accès
		$site_acces = $site->checkAcces($db, $user, $pass);
		$tpl->set('PAGE_ACCES', $site);
		
		if(!$page->isPublic())
			if(!$site_acces->isAdmin() && !$page->checkAcces($db, $user, $pass))
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurAccessDenied.htm',__FILE__,__LINE__);
		
		$page_source = $page->getContentHTML();
		$tpl->set('page', $page);
		if ($site_acces && ($site_acces->isAdmin() || $site_acces->canModifier())){
			if($site_acces->isAdmin() && !$page->isPublic())
				$tpl->set('SHOW_GESTION_ACCES', true);
			$page_source .= $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurPageControl.htm',__FILE__,__LINE__);;
		}
		return array($page->getTitre(), $page_source);
	}

}
?>