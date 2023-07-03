<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_FouillerLieu2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		$totalPr = 0;
		$totalPa = 0;
		$paCostPerItem = 2;
		
		//Trouver les informations concernant les items dans le lieu
		$i=0;
		while( $item = $perso->getLieu()->getItems($db, $i++)){
			
			if(isset($_POST[$item->getInvId() . '_qte'])
			&& is_numeric($_POST[$item->getInvId() . '_qte'])
			&& $_POST[$item->getInvId() . '_qte'] > 0){
			
				$totalPr += $item->getPr() * $_POST[$item->getInvId() . '_qte'];
				$totalPa += $paCostPerItem;
				
			}
			
			if ($item->getQte() < $_POST[$item->getInvId() . '_qte'])
				return fctErrorMSG('Vous ne pouvez pas transférer plus que le lieu contient.', '?popup=1&m=Action_FouillerLieu');
		}
		
		
		if($perso->getPa() < $totalPa)
			return fctErrorMSG('Vous n\'avez pas assez de PA.','?popup=1&m=Action_FouillerLieu');
		
		if (($perso->getPrMax() - $perso->getPr()) < $totalPr)
			return fctErrorMSG('Vous ne disposez pas d\'assez de Pr pour effectuer cette action.','?popup=1&m=Action_FouillerLieu');
		
		
		
		
		$i=0; $itemsList = '';
		while( $item = $perso->getLieu()->getItems($db, $i++)){
		
			if(isset($_POST[$item->getInvId() . '_qte'])
			&& is_numeric($_POST[$item->getInvId() . '_qte'])
			&& $_POST[$item->getInvId() . '_qte'] > 0){
				
				if($itemsList!='')
					$itemsList .= ', ';
				$itemsList .= $item->getNom();
				
				Member_Item::transfererItemLieuVersPerso($db, $item, $perso, $_POST[$item->getInvId() . '_qte']);
			}
		}
		
		
		$perso->refreshInventaire($db);
		$perso->changePa('-', $totalPa);
		$perso->setPa($db);
		
		
		if ($itemsList != '')
			Member_He::add($db, 'System', $perso->getId(), 'donner', "Objet(s) ramassé(s) dans le lieu [i]" . $perso->getLieu()->getNom() . "[/i]: \n" . $itemsList);
		
		
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>
