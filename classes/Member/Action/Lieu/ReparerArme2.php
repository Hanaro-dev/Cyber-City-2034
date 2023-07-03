<?php
/** Gestion de l'interface de réparation des armes
*
* @package Member_Action
*/
class Member_Action_Lieu_ReparerArme2
{
	public static function generatePage(&$tpl, &$session, &$account, &$perso)
	{
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante	//BUT: Démarrer un template propre à cette page
		
		$errorUrl = '?popup=1&amp;m=Action_Lieu_ReparerArme';
		
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.', $errorUrl);
		
		//Vérifier si un item à été sélectionné
		if(!isset($_POST['invId']) || !is_numeric($_POST['invId']))
			return fctErrorMSG('Vous devez sélectionner un item à réparer.', $errorUrl);
			
		if($perso->getLieu()->getQteMateriel()<=0)
			return fctErrorMSG('Le lieu ne contient plus de matériel.', $errorUrl);
		
		//Valider si l'arme est présente dans l'inventaire
		$i=0;
		$found=false;
		while( $item = $perso->getInventaire($i++))
		{
			if($item instanceof Member_ItemArme)
			{
				if($item->getInvId() == $_POST['invId'])
				{
					$found = true;
					break;
				}
			}
		}
		
		if(!$found)
			return fctErrorMSG('L\'item ne semble pas être une arme valide en votre possession.', $errorUrl);
		

		$msg = '';
						
		//Calculer le % de réussite
		switch($item->getTypeTech())
		{
			case 'arme_lancee':
				$chanceReussite = 0;
				break;
			case 'arme_blanche':
				$chanceReussite = $perso->getChancesReussite('forg');
				break;
			case 'arme_cac':
				$chanceReussite = $perso->getChancesReussite('forg');
				break;
			case 'arme_paralysante':
				$chanceReussite = $perso->getChancesReussite('armu');
				break;
			case 'arme_feu':
				$chanceReussite = $perso->getChancesReussite('armu');
				break;
		}
		
		if (DEBUG_MODE)
			echo "\n%dom:" .$item->getPercDommage();
		if (DEBUG_MODE)
			echo "\n%complex:" .$item->getPercComplexite();
		
		
		
		//Calculer le cout $/Pa de la réparation
		$coutPa		= 10;
		
		if($perso->getPa() <= $coutPa)
			return fctErrorMSG('Vous n\'avez pas assez de Pa pour effectuer une réparation. (Requiert ' . $coutPa . 'PA)', $errorUrl);
		
		
		
		//Débuter la tentative de réparation
		$de = rand(1,100);
		
		
		if (DEBUG_MODE)
			echo "\nde/%reussite:" .$de . '/' . $chanceReussite . "\n";
			
			
		if($de < $chanceReussite)
		{
			//Réussite
			
			$msg .= "Vous arrivez à réparer votre [i]" . $item->getNom() . "[/i].";
			$perso->changePa('-', $coutPa);
		
			//Calculer le nombre de Pts de réparés
			switch($item->getTypeTech())
			{
				case 'arme_lancee':
					break;
				case 'arme_feu':
					$msg .= "\n" . $perso->setComp(array('ARMU' => rand(2,6)));
					break;
				case 'arme_blanche':
					$msg .= "\n" . $perso->setComp(array('FORG' => rand(2,6)));
					break;
				case 'arme_cac':
					$msg .= "\n" . $perso->setComp(array('FORG' => rand(2,6)));
					break;
				case 'arme_paralysante':
					$msg .= "\n" . $perso->setComp(array('ARMU' => rand(2,6)));
					break;
			}
			$lvl=30;
			
			
			
			if(($item->getResistanceMax() - $item->getResistance()) <= $lvl)
				$newResist = $item->getResistanceMax();
			else
				$newResist = $item->getResistance() + $lvl;
			
			//Mettre à jour la résistance de l'item
			$query = 'UPDATE ' . DB_PREFIX . 'item_inv'
						. ' SET		inv_resistance=:resistance'
						. ' WHERE	inv_id = :itemId'
							. ' AND inv_persoid = :persoId'
						. ' LIMIT 1;';
			$prep = $db->prepare($query);
			$prep->bindValue(':resistance',		$newResist,			PDO::PARAM_INT);
			$prep->bindValue(':itemId',			$item->getInvId(),	PDO::PARAM_INT);
			$prep->bindValue(':persoId',		$perso->getId(),	PDO::PARAM_INT);
			$prep->execute($db, __FILE__, __LINE__);
			$prep->closeCursor();
			$prep = NULL;
			
			$msg .= "\n" . $perso->setStat(array('INT' => '+01', 'DEX' => '+01', 'AGI' => '-02' ));
		}
		else
		{
			//Échec
			$msg .= "Vous essayer de réparer votre [i]" . $item->getNom() . "[/i], mais c'est un échec.";
			$perso->changePa('-', $coutPa);
			
			$msg .= "\n" . $perso->setStat(array('INT' => '+01', 'DEX' => '+01', 'AGI' => '-02' ));
		}
		
		//Retirer le materiel utilisé du lieu
		$query = 'UPDATE ' . DB_PREFIX . 'lieu'
				. ' SET qteMateriel=qteMateriel-:qte'
				. ' WHERE id=:lieuId;';
		$prep = $db->prepare($query);
		$prep->bindValue(':qte',		1,								PDO::PARAM_INT);
		$prep->bindValue(':lieuId',		$perso->getLieu()->getId(),		PDO::PARAM_INT);
		$prep->execute($db, __FILE__, __LINE__);
		$prep->closeCursor();
		$prep = NULL;
		
		$perso->setPa();
		
		Member_He::add('System', $perso->getId(), 'reparer', $msg);
		
		//Ajout dans le He des gens du lieu
		$i=0;
		$arrPersoLieu = array();
		while( $tmp = $perso->getLieu()->getPerso($perso, $i++)) {
			if($tmp->getId() != $perso->getId())
				$arrPersoLieu[count($arrPersoLieu)] = $tmp->getId();
		}

		Member_He::add($perso->getId(), $arrPersoLieu, 'reparer', "Vous voyez quelqu'un utiliser l'atelier de réparation", HE_AUCUN, HE_UNIQUEMENT_MOI);
		
		if(!DEBUG_MODE)
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
