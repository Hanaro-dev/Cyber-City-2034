<?php
/** Gestion de l'action d'équiper un item. Cette page est utilisée UNIQUEMENT par AJAX. des # d'erreur sont retourné, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_InventaireCharger{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		$actionPa = 10;
		
		if (!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id'])))
			die('00|' . rawurlencode('Vous devez sélectionner un item pour effectuer cette action.'));
			
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			die($_POST['id'] . '|' . rawurlencode('Votre n\'êtes pas en état d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die($_POST['id'] . '|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		
		
		
		//Trouver en inventaire l'item que l'on souhaite charger
		$i=0; $item = null;
		while( $tmp = $perso->getInventaire($db, $i++))
			if($tmp->getInvId() == $_POST['id'])
				$item = $tmp;		
		if(empty($item))
			die($_POST['id'] . '|' . rawurlencode('Cet item ne vous appartiend pas. (cheat)'));
			
		if($item->getMunition()>=$item->getMunitionMax())
			die($_POST['id'] . '|' . rawurlencode('Votre arme est déjà remplie à pleine capacité.'));
			
		
		if (!isset($_POST['munid'])){ // Proposer des munitions pour les charger dans l'arme
			//Trouver les munitions compatibles possédés en inventaire
			$query = "SELECT mun.inv_id, mun.inv_qte, mundb.db_nom
						FROM " . DB_PREFIX . "item_db as armedb
						LEFT JOIN " . DB_PREFIX . "item_db_armemunition as tam ON (tam.db_armeid = armedb.db_id)
						LEFT JOIN " . DB_PREFIX . "item_inv as mun ON (mun.inv_persoid=" . $perso->getId() . " AND mun.inv_dbid=tam.db_munitionid)
						LEFT JOIN " . DB_PREFIX . "item_db as mundb ON (mundb.db_id = mun.inv_dbid)
						WHERE armedb.db_id = " . $item->getDbId() . ";";
			$result = $db->query($query,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);
			
			if(mysql_num_rows($result)==0)
				die($_POST['id'] . '|' . rawurlencode('Vous ne possèdez aucune munition compatible avec cette arme.'));
				
			$msg = '';
			while($arr = mysql_fetch_assoc($result))
				$msg .= "<a href=\"#\" onclick=\"submitMunForm('?popup=1&m=Action_InventaireCharger'," . $_POST['id'] . "," . $arr['inv_id'] . ");\">" . stripslashes($arr['db_nom']) . "</a><br />";
			
			die($_POST['id'] . '|' . $msg);
			
		}else{ // Charger les munitions dans l'arme
			//Trouver en inventaire les munitions que l'on souhaite charger
			$i=0; $mun = null;
			while( $tmp = $perso->getInventaire($db, $i++))
				if($tmp->getInvId() == $_POST['munid'])
					$mun = $tmp;
			if(empty($mun))
				die($_POST['id'] . '|' . rawurlencode('Cette munition ne vous appartiend pas. (cheat)'));
			
			$munQte = $mun->getQte();
			$munReq = $item->getMunitionMax() - $item->getMunition();
			
			if ($munReq > $munQte){ //Plus de munition nécéssaire que disponible, tout charger
				$mun->setQte($db, 0);
				$item->setMunition($db, $munQte);
			}else{
				$mun->setQte($db, $munQte-$munReq);
				$item->setMunition($db, $item->getMunitionMax());
			}
			
			
			$perso->changePa('-', $actionPa);
			$perso->setPa($db);
			
			$perso->refreshInventaire($db); //Recalculer l'inventaire (les PR)
			die($_POST['id'] . '|OK|' . $perso->getPa() . '|' . $perso->getPr()); //Tout est OK
		}
	
	}
}
?>