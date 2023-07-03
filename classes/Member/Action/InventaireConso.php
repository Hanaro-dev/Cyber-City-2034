<?php
/** Gestion de l'action de consommer un item. Cette page est utilis�e UNIQUEMENT par AJAX. des # d'erreur sont retourn�, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_InventaireConso
{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		$actionPa = 3;
		
		if (!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id'])))
			die('00|' . rawurlencode('Vous devez s�lectionner un item pour effectuer cette action.'));
			
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			die($_POST['id'] . '|' . rawurlencode('Votre n\'�tes pas en �tat d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die($_POST['id'] . '|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		
		
		
		//Trouver en inventaire l'item que l'on souhaite �quiper
		$i=0; $item = null;
		while( $tmp = $perso->getInventaire($db, $i++))
			if($tmp->getInvId() == $_POST['id'])
				$item = $tmp;
		
		if(empty($item))
			die($_POST['id'] . '|' . rawurlencode('Cet item ne vous appartiend pas. (cheat)'));
		
		
		
		
		//Si l'item est une nourriture, le supprimer et ajouter les PN
		if ($item instanceof Member_ItemNourriture){
			if ($item->getQte()==1)
				$query = 'DELETE FROM ' . DB_PREFIX . 'item_inv
							WHERE inv_id=' . $_POST['id'] . ';';
			else
				$query = 'UPDATE ' . DB_PREFIX . 'item_inv
							SET inv_qte = inv_qte - 1
							WHERE inv_id=' . $_POST['id'] . ';';
			$db->query($query, __FILE__, __LINE__);
			
			$perso->changePn('+', $item->getPn());
			$perso->setPn($db);
			
			
		//Si l'item est une drogue, 
		}elseif ($item instanceof Member_ItemDrogueDrogue){
		
			//Activer la dur�e de la drogue
			$remiseleft = rand($item->getDuree()-1, $item->getDuree()+1); //Dur�e un peu al�atoire
			$query = 'UPDATE ' . DB_PREFIX . 'item_inv
						SET inv_remiseleft = ' . $remiseleft . '
						SET inv_qte = inv_qte -1
						WHERE	inv_persoid=' . $perso->getId() . '
							AND inv_id='. $item->getInvId() . ';';
			$db->query($query, __FILE__, __LINE__);
			
			$perso->changePa('+', $item->getBoostPa());
			
			$perso->changePv('+', $item->getBoostPv());
			$perso->setPv($db);
			
		}else{
			die($_POST['id'] . '|' . rawurlencode('Vous ne pouvez pas consommer ce type d\'item.'));
		}
		
		
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		
		$perso->refreshInventaire($db); //Recalculer l'inventaire (les PR)
		
		die($_POST['id'] . '|OK|' . $perso->getPa() . '|' . $perso->getPr() . '|' . $perso->getPn()); //Tout est OK
	}
}
?>