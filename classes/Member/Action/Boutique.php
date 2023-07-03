<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Boutique{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Déclaration des variables pour cette action
		$pacost = array();
		$pacost["achat"] = 10;
		$pacost["nego"] = 20;
		$pacost["vol"] = 40;
		
		//Créer le template
		$tpl->set("PA_COST",$pacost);
	
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
		
		
		//Lister l'inventaire de la boutique
		//LISTER TOUT LES ITEMS EN VENTE DANS LA BOUTIQUE
		$i=0; $items=array();
		while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++))
			if ($item->getBoutiquePrixVente()>=0)
				$items[$i++] = $item;
		$tpl->set('INV_BOUTIQUE', $items);
		
		
		//Lister les cartes bancaires + les items en inventaire que la boutique se propose d'acheter
		$i=0; $e=0; $f=0; $cartes=array(); $inv=array();
		while( $item = $perso->getInventaire($db, $i++)){
			if($item instanceof Member_ItemCartebanque){
				$cartes[$e++]  = $item;
			}
			$j=0;
			while( $item2 = $perso->getLieu()->getBoutiqueInventaire($db, $j++))
				if($item->getDbId() == $item2->getDbId() && $item2->getBoutiquePrixAchat()>=0)
					$inv[$f++] = array('inv'=>$item,'boutique'=>$item2);
		}
		if (isset($cartes))
			$tpl->set('CARTES', $cartes);
		$tpl->set('INV_PERSO', $inv);
		
		
		//Définir les accès d'administration
		if($perso->getLieu()->getProprioid() == $perso->getId())
			$tpl->set('admin', true);
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/boutique.htm',__FILE__,__LINE__);
	}
}
?>