<?php
/** Gestion de l'interface de l'attaque
*
* @package Member_Action
*/
class Member_Action_Attaquer{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Vous n\'êtes pas en état de pouvoir effectuer cette action.');
		
		//Générer la liste des personnages présent dans le lieu actuel
		$i=0; $e=0;
		$arrPersos = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if($tmp->getId() != $perso->getId()){
				//Info sur le perso
				$arrPersos[$e]['perso'] = $tmp;
				$arrPersos[$e]['disabled'] = '';
				
				//Info sur l'arme
				$arme = $tmp->getArme($db);
				if($arme instanceof Member_Arme)
					$arrPersos[$e]['arme'] = $arme->getNom();
				else
					$arrPersos[$e]['arme'] = '- n/a -';
				
				//Info TXT sur l'état du perso
				if ($tmp->isNormal())
					$arrPersos[$e]['etat'] = "En santé";
				elseif ($tmp->isAutonome())
					$arrPersos[$e]['etat'] = "Légèrement blessé";
				elseif($tmp->isConscient())
					$arrPersos[$e]['etat'] = "Blessé gravement";
				elseif($tmp->isVivant())
					$arrPersos[$e]['etat'] = "Inconscient";
				else{
					$arrPersos[$e]['etat'] = "Mort";
					$arrPersos[$e]['disabled'] = 'DISABLED';
				}
					
				$e++;
			}
		}
		$tpl->set('PERSOS', $arrPersos);
		
		
		//Afficher l'arme utilisée
		if($perso->getArme($db) instanceof Member_ItemArme)
			$tpl->set('ARME_NOM', $perso->getArme($db)->getNom());
		else
			$tpl->set('ARME_NOM', "Mains nues");
		
		//Créer la liste des tours possibles
		$i=0;
		while ((self::coutPaTotal($db, $perso, $i+1)<=$perso->getPa() || $i<9) && $i<10){
			$totalPa = self::coutPaTotal($db, $perso, $i+1);
			$tours[$i] = array('no'=>$i+1, 'pa'=>$totalPa, 'statut'=> ($totalPa<=$perso->getPa()) ? '' : 'DISABLED');
			$i++;
			
		}
		$tpl->set('TOURS', $tours);
		
		
		
		//Créer la liste des portées possibles
		$arr = array();
		$arr[0]['code'] = 'TC';
		$arr[0]['txt'] = 'Contact direct (Bout portant)';
		
		if($perso->getArme($db) instanceof Member_ItemArmeFeu){
			//Permettre les portées non-directes
			$arr[1]['code'] = 'C';
			$arr[1]['txt'] = 'Courte portée';
			$arr[2]['code'] = 'M';
			$arr[2]['txt'] = 'Moyenne';
			$arr[3]['code'] = 'L';
			$arr[3]['txt'] = 'Longue distance';
			$arr[4]['code'] = 'TL';
			$arr[4]['txt'] = 'Très longue distance';
		}
		$tpl->set('PORTEE', $arr);
		
		
		
		//Créer la liste des zones ciblables
		$tour_cible['pa'] = self::coutPaPourUnTour($db, $perso, 1) + 15;
		$tpl->set('tour_cible', $tour_cible);
		
		$zones[0] = array('tech'=>'Tete','nom'=>'Tete');
		$zones[1] = array('tech'=>'Torse','nom'=>'Torse');
		$zones[2] = array('tech'=>'Bras','nom'=>'Bras');
		$zones[3] = array('tech'=>'Main','nom'=>'Main');
		$zones[4] = array('tech'=>'Jambe','nom'=>'Jambe');
		$zones[5] = array('tech'=>'Pied','nom'=>'Pied');
		$tpl->set('ZONES', $zones);
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/attaquer.htm',__FILE__,__LINE__);
	}
	
	public static function coutPaPourUnTour(&$db, &$perso, $noDuTour){
		$coutTours = array(25,20,15,10,5);
		
		return $perso->getArme($db)->getPa() + ($noDuTour > count($coutTours)) ? $coutTours[count($coutTours)-1] : $coutTours[$noDuTour-1];
	}
	
	public static function coutPaTotal(&$db, &$perso, $nbrTours){
		if ($nbrTours<0)
			return 0;
		
		$coutPa=0;
		for ($i=0;$i<$nbrTours;$i++)
			$coutPa+=self::coutPaPourUnTour($db, $perso, $i+1);
		return $coutPa;
	}
	
	public static function porteePlusPetite($a, $b){
		return (self::porteeVersInt($a) < self::porteeVersInt($b));
	}
	
	public static function porteeVersInt($portee){
		switch($portee){
			case 'TC':	return 1;	break;
			case 'C':	return 2;	break;
			case 'M':	return 3;	break;
			case 'L':	return 4;	break;
			case 'TL':	return 5;	break;
		}
	}
}
?>