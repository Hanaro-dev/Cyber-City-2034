<?php
/** Gestion de l'interface des déplacement
*
* @package Member_Action
*/
class Member_Action_Deplacement2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
				global $timerTotal;
		
		//VALIDATION DE BASE:
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.', '?popup=1&m=Action_Deplacement');
		
		if(!isset($_POST['moveto']))
			return fctErrorMSG('Vous devez sélectionner le lieu vers lequel vous désirez vous déplacer.', '?popup=1&m=Action_Deplacement');
			
		if(!isset($_POST['RAD_typeaction']))
			return fctErrorMSG('Vous devez sélectionner le type d\'action.', '?popup=1&m=Action_Deplacement');
			

		
		//GÉNÉRER LES DONNÉES NÉCÉSSAIRE À L'ACTION:
		//Générer la liste des lieux connexes
		$i=0;
		$found=false;
		while(!$found && $lien = $perso->getLieu()->getLink($db, $i++, $perso->getId()))
			if ($lien->getId() == $_POST['moveto'])
				$found=true;
		if(!$found)
			return fctErrorMSG('Le lieu que vous avez sélectionné n\'existe pas.', '?popup=1&m=Action_Deplacement');
		$tpl->set('LIEU', $lien);
		
		
		//Générer la liste des personnages (à qui le joueur veux tenir la porte)
		$i=0; $e=0;
		$arrPersoTenirPorte = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() != $perso->getId())
				if(isset($_POST['t_' . $tmp->getId()]))
					$arrPersoTenirPorte[$e++] = $tmp->getId();
					
		if($_POST['RAD_typeaction']=='tenirporte' && count($arrPersoTenirPorte)==0)
			return fctErrorMSG('Si vous désirer uniquement tenir la porte, vous devez sélectionner au moins une personne à qui tenir la porte.', '?popup=1&m=Action_Deplacement');
		$tpl->set('PERSOS', $arrPersoTenirPorte);
		
		
		
		
		//VALIDATION SELON LES DONNÉES DYNAMIQUES (Cout/Pa):
		//Calcul du cout en PA de l'action
		$coutPa=0;
		
		if(count($arrPersoTenirPorte)>0)
			$coutPa+=5;
		
		if($_POST['RAD_typeaction']=='deplacer')
			$coutPa+= $lien->getPa();
		
		if(isset($_POST['CHK_furtif']))
			$coutPa+=10 ;
		
		if($perso->getPa() <= $coutPa)
			return fctErrorMSG('Vous n\'avez pas assez de PA pour effectuer cette action.', '?popup=1&m=Action_Deplacement');
		
		
		//Calculer le cout monétaire de l'action
		if($perso->getCash() < $lien->getCout())
			return fctErrorMSG('Vous n\'avez pas assez d\'argent pour effectuer cette action.', '?popup=1&m=Action_Deplacement');
		
		
		
		
		
		
		
		
		//GESTION DES PROTECTION
		$protection = $lien->getProtection();
		
		//Si la porte est tenue, retirer les protections
		if (!empty($protection)){
			$query = 'SELECT id
						FROM ' . DB_PREFIX . 'lieu_tenirporte
						WHERE de="' . $perso->getLieu()->getNomTech() . '"
							AND vers="' . $lien->getNomTech() . '"
							AND qui=' . $perso->getId() . '
						LIMIT 1;';
			$result = $db->query($query,__FILE__,__LINE__);
			if (mysql_num_rows($result)!=0){ //La porte est tenu, retirer la protection
				$protection = null;
				$porteTenueId = mysql_result($result,0);
			}
		}
		
		
		
		
		
		
		//ÉTAPE-3 : En cas de DIGIPASS ou de CLÉ, vérifier la validité de ceux-ci (si le joueur les à entré)
		if(!empty($protection)){
			if($protection=='pass' && isset($_POST['pass']))
				$code = $_POST['pass'];
			
			if($protection=='cle' && isset($_POST['cle'])){
				$i=0; $e=0;
				$found=false;
				while( $item = $perso->getInventaire($db, $i++) && $found==false)
					if(is_a($item, 'Member_ItemCle'))
						if($item->getId() == $_POST['cle'])
							$found=true;
				$code = $item->getCode();
			}
			
			//Valider le code, si OK, retirer la protection
			if (isset($code))
				if($code == $lien->getPass())
					$protection=null;
		}
		
		
		
		
		
		
		//S'il y a une protection d'active; Afficher la bonne page en ce qui concerne les protections
		if(!empty($protection)){
			$tpl->set('moveto'					, $_POST['moveto']);
			$tpl->set('RAD_typeaction'			, $_POST['RAD_typeaction']);
			$tpl->set('CHK_furtif'				, (isset($_POST['CHK_furtif'])) ? $_POST['CHK_furtif'] : null);
			$tpl->set('CHK_keepcurrentaction'	, (isset($_POST['CHK_keepcurrentaction'])) ? $_POST['CHK_keepcurrentaction'] : null);
			$tpl->set('arrPorteTenuA'			, $arrPersoTenirPorte);
			
			switch ($protection){
				case 'cle':		//Requiert une clé (Générer la liste des clés en inventaire)
					$i=0; $e=0;
					while( $item = $perso->getInventaire($db, $i++))
						if(is_a($item, 'Member_ItemCle'))
							$cle[$e++] = $item;
					$tpl->set('CLES', $cle);
					
					return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/deplacement_cle.htm',__FILE__,__LINE__);
					break;
				case 'pass':	//Requiert un code DIGIPASS
					if(isset($code)) // un code a été entré mais la protection est toujours en place, donc le code était mauvais.
						$tpl->set('WRONGPASS', true);
					
					if(isset($_POST['CHK_keepcurrentaction']))
						$tpl->set('CHK_keepcurrentaction', $_POST['CHK_keepcurrentaction']);
						
					if(isset($_POST['CHK_furtif']))
						$tpl->set('CHK_furtif', $_POST['CHK_furtif']);
					$tpl->set('RAD_typeaction', $_POST['RAD_typeaction']);
					$tpl->set('moveto', $_POST['moveto']);
					
					return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/deplacement_digipass.htm',__FILE__,__LINE__);
					break;
				case 'ban':		//Banni du lieu
					return fctErrorMSG('Vous êtes actuellement banni de ce lieu, vous ne pouvez pas y accéder.', '?popup=1&m=Action_Deplacement');
					break;
			}
		}
		
		
		
		//EFFECTUER LE DÉPLACEMENT
		//Retirer l'argent
		if ($lien->getCout() > 0){
			$perso->changeCash('-', $lien->getCout());
			$perso->setCash($db);
		}
		
		//Retirer les PA
		if ($lien->getPa() > 0){
			$perso->changePa('-', $coutPa);
			$perso->setPa($db);
		}
		
		$msg = '';
		
		
		//Si la porte à été tenue, enregistrer la 'porte tenue' puis envoyer le message aux personnes concernées.
		$expire_time = mktime (date("H")+TENIRPORTE_TIMEOUT, date("i"), date("s"), date("m"), date("d"), date("Y"));
		$arrTenirPortePersoId=array();
		foreach($arrPersoTenirPorte as $pid){
			$query='INSERT IGNORE INTO ' . DB_PREFIX . 'lieu_tenirporte
						(de,vers,qui,expiration) 
						VALUES (
							"' . $perso->getLieu()->getNomTech() . '",
							"' . $lien->getNomTech() . '",
							' . $pid . ',
							' . $expire_time . '
						);';
			$db->query($query,__FILE__,__LINE__);
			$arrTenirPortePersoId[count($arrTenirPortePersoId)] = $pid;
		}
		if(count($arrTenirPortePersoId)>0)
			Member_He::add($db, $perso->getId(), $arrTenirPortePersoId, 'move', "La porte est tenue ouverte du lieu " . $perso->getLieu()->getNom() . " vers " . $lien->getNom());



		
		
		if($_POST['RAD_typeaction']=='deplacer') {
	
			//Calculer la réussite d'un déplacement furtif
			$deplacementFurtif = false;
			if (isset($_POST["CHK_furtif"])) {
				
				$de = rand(0,100);
				$chance = $perso->getChancesReussite($db, 'FRTV');
				if ($de < $chance) {//Reussite			
					
					$msg .= "Vous effectuez un déplacement furtif avec succès et personne ne vous voit.\n";
					$msg .= $perso->setStat( $db, array('PER' => '+01', 'DEX' => '+01', 'FOR' => '-02' ));
					$msg .= $perso->setComp( $db, array('FRTV' => rand(1,3) ));
					
					$deplacementFurtif = true;
					
				}else{ //Echec
					
					$msg .= "Vous tentez un déplacement furtif mais c'est un échec.\n";
					$msg .= $perso->setStat( $db, array('AGI' => '+01', 'DEX' => '-01'));
					$msg .= $perso->setComp( $db, array('FRTV' => rand(1,3) ));
					
				}
			}
		
		
		
			//Envoyer les message de déplacement
			if(!$deplacementFurtif){
				
				//Faire la liste de tout les personnages du lieu de départ
				$i=0; $e=0;
				$arrFrom=array();
				while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
					if($tmp->getId() != $perso->getId())
						$arrFrom[$e++] = $tmp->getId();
				Member_He::add($db, $perso->getId(), $arrFrom, 'move', "Vous voyez une personne sortir du lieu où vous vous trouvez.", HE_AUCUN, HE_TOUS);
				
				
				//Faire la liste de tout les personnages du lieu de destination
				$i=0; $e=0;
				$arrTo=array();
				while( $tmp = $lien->getPerso($db, $perso, $i++))
					if($tmp->getId() != $perso->getId()) //Théoriquement cette validation ne peux JAMAIS arriver à ==
						$arrTo[$e++] = $tmp->getId();
				Member_He::add($db, $perso->getId(), $arrTo, 'move', "Vous voyez une personne entrer dans le lieu où vous vous trouvez.", HE_AUCUN, HE_TOUS);
				
			}
			
			

			
			
			//Je suis le joueur à qui on à tenu la porte, je passe, donc effacer mon accès (si accès il y a).
			if (isset($porteTenueId)){
				$query = "DELETE FROM " . DB_PREFIX . "lieu_tenirporte
							WHERE id=" . $porteTenueId . ";";
				$db->query($query,__FILE__,__LINE__);
			}
			
			
			
			//SUPPRIMER L'action courrante
			if (!isset($_POST['CHK_keepcurrentaction'])){
				$query = 'UPDATE ' . DB_PREFIX . 'perso
							SET current_action=""
							WHERE id=' . $perso->getId() . ';';
				$db->query($query,__FILE__,__LINE__);
			}
			
			//Effectuer le déplacement
			$msg .= "Vous vous déplacez vers " . $lien->getNom() . ".";
			$query = 'UPDATE ' . DB_PREFIX . 'perso
						SET lieu="' . $lien->getNomTech() . '"
						WHERE id =' . $perso->getId() . ';';
			$db->query($query,__FILE__,__LINE__);
			
			//Ajouter le message du déplacement
			Member_He::add($db, 0, $perso->getId(), 'move', $msg, HE_AUCUN, HE_TOUS);
		}
	
	
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>
