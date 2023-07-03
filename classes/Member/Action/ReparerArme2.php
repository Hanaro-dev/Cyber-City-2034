<?php
/** Gestion de l'interface de réparation des armes
*
* @package Member_Action
*/
class Member_Action_ReparerArme2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.', '?popup=1&m=Action_ReparerArme');
		
		if(!isset($_POST['invId']))
			return fctErrorMSG('Vous devez sélectionner un item à réparer.', '?popup=1&m=Action_ReparerArme');
		
		
		//Valider si l'arme est présente dans l'inventaire
		$i=0; $found=false;
		while( $item = $perso->getInventaire($db, $i++)){
			if($item instanceof Member_ItemArme){
				if($item->getInvId() == $_POST['invId']){
					$found = true;
					break;
				}
			}
		}
		
		if(!$found)
			return fctErrorMSG('L\'item ne semble pas être une arme valide en votre possession.', '?popup=1&m=Action_ReparerArme');
		

		$msg = '';
						
		//Calculer le % de réussite
		switch($item->getTypeTech()){
			case 'arme_lancee':

				break;
			
			case 'arme_feu':
				$chanceReussite =	(
									  $perso->getChancesReussite($db,'ARMF') * 1
									+ $perso->getChancesReussite($db,'FORG') * 2
									+ $perso->getChancesReussite($db,'MECA') * 1
									) /4;
				$chanceReussite = round(($chanceReussite + (100-$item->getPercDommage() + 100-$item->getPercComplexite())/2) /2);
				break;
			
			case 'arme_blanche':
				$chanceReussite =	(
									  $perso->getChancesReussite($db,'ARMB') * 1
									+ $perso->getChancesReussite($db,'FORG') * 2
									+ $perso->getChancesReussite($db,'ARMU') * 1
									) /4;
				$chanceReussite = round(($chanceReussite + (100-$item->getPercDommage() + 100-$item->getPercComplexite())/2) /2);
				break;
		}
		if (DEBUG_MODE) $msg .= "\n%dom:" .$item->getPercDommage();
		if (DEBUG_MODE) $msg .= "\n%complex:" .$item->getPercComplexite();
		
		//Calculer le cout $/Pa de la réparation
		$coutCash 	= round(($item->getPercDommage() / 20) * ($item->getResistanceMax() - $item->getResistance()),2);
		$coutPa		= floor((100-$chanceReussite)/10 * $item->getPercDommage()/10);
		
		if($perso->getCash() < $coutCash)
			return fctErrorMSG('Vous n\'avez pas assez d\'argent pour effectuer une réparation.', '?popup=1&m=Action_ReparerArme');
		
		if($perso->getPa() < $coutPa)
			return fctErrorMSG('Vous n\'avez pas assez de Pa pour effectuer une réparation.', '?popup=1&m=Action_ReparerArme');
		
		
		
		//Débuter la tentative de réparation
		$de = rand(1,100);
		if (DEBUG_MODE) $msg .= "\nde/%reussite:" .$de . '/' . $chanceReussite . "\n";
		if($de < $chanceReussite){
			//Réussite
			
			$msg .= "Vous arrivez à réparer l'item.";
			$perso->changePa('-', $coutPa);
			$perso->changeCash('-', $coutCash);
			
			
			//Calculer le nombre de Pts de réparés
			switch($item->getTypeTech()){
				case 'arme_lancee':
					
					break;
				case 'arme_feu':
					$lvl = floor((
							  $perso->getCompRealLevel($db,'ARMF') * 1
							+ $perso->getCompRealLevel($db,'FORG') * 2
							+ $perso->getCompRealLevel($db,'MECA') * 1
							) /4);
					$msg .= "\n" . $perso->setComp($db, array('FORG' => rand(2,6), 'ARMF' => rand(1,3), 'MECA' => rand(1,3) ));
					break;
				case 'arme_blanche':
					$lvl = floor((
							  $perso->getCompRealLevel($db,'ARMB') * 1
							+ $perso->getCompRealLevel($db,'FORG') * 2
							+ $perso->getCompRealLevel($db,'ARMU') * 1
							) /4);
					$msg .= "\n" . $perso->setComp($db, array('FORG' => rand(2,6), 'ARMB' => rand(1,3),  'ARMU' => rand(1,3)));
					break;
			}
			$lvl++;
			if (DEBUG_MODE) $msg .= "\nLvl: (" . $perso->getCompRealLevel($db,'ARMF') . '+' . $perso->getCompRealLevel($db,'FORG') . '*2+' . $perso->getCompRealLevel($db,'MECA') . ') /4=' . $lvl . "\n";
			
			if(($item->getResistanceMax() - $item->getResistance()) <= $lvl)
				$newResist = $item->getResistanceMax();
			else
				$newResist = $item->getResistance() + $lvl;
			
			//Mettre à jour la résistance de l'item
			$query = 'UPDATE ' . DB_PREFIX . 'item_inv
						SET		inv_resistance=' . $newResist . '
						WHERE	inv_id = ' . $item->getInvId() . '
							AND inv_persoid = ' . $perso->getId(). ';';
			$db->query($query, __FILE__, __LINE__);
			
			$msg .= "\n" . $perso->setStat($db, array('INT' => '+01', 'DEX' => '+01', 'AGI' => '-02' ));
		}else{
			//Échec
			$msg .= "Vous essayer de réparer l'item, mais c'est un échec.";
			$perso->changePa('-', $coutPa);
			$perso->changeCash('-', $coutCash);
			$msg .= "\n" . $perso->setComp($db, array('FORG' => rand(1,3) ));
			$msg .= "\n" . $perso->setStat($db, array('INT' => '+01', 'DEX' => '+01', 'AGI' => '-02' ));
		}
			
		
		$perso->setPa($db);
		$perso->setCash($db);
		
		Member_He::add($db, 'System', $perso->getId(), 'reparer', $msg);
		
		
		if(!DEBUG_MODE)
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>