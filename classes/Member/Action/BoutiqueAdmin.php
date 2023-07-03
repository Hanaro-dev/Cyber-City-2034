<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_BoutiqueAdmin{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//Définir les accès d'administration
		if($perso->getLieu()->getProprioid() != $perso->getId())
			return fctErrorMSG('Vous devez être propriétaire du lieu pour pouvoir l\'administrer.', '?popup=1&m=Action_Boutique', array('perso' => $perso, 'lieu' => $lieu));
		
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
			
		$tpl->set('CASH_CAISSE', $perso->getLieu()->getBoutiqueCash());
		
		//LISTER TOUT LES ITEMS EN VENTE DANS LA BOUTIQUE
		$i=0; $items=array();
		while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++))
			$items[$i++] = $item;
		$tpl->set('INV_BOUTIQUE', $items);
		
		//LISTER TOUT LES ITEMS QUE LE PERSO POSSÈDE SUR LUI
		$i=0; $items=array();
		while( $item = $perso->getInventaire($db, $i++))
			if($item instanceof Member_ItemDrogueDrogue){
			}else{
				$items[$i++] = $item;
			}
		$tpl->set('INV_PERSO', $items);
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/boutiqueAdmin.htm',__FILE__,__LINE__);
	}
}
?>