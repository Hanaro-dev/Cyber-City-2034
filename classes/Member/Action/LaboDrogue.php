<?php
/** Gestion d'un laboratoire de drogue
*
* @package Member_Action
*/
class Member_Action_LaboDrogue{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		
		//Valider si le lieu actuel est un labo
		if(!$perso->getLieu()->isLaboDrogue($db))
			return fctErrorMSG('Ce lieu n\'est pas un laboratoire de drogue.');
		
		
		
		
		//LISTER TOUT LES ITEMS QUE LE PERSO POSSÈDE SUR LUI
		$i=0; $items=array();
		while( $item = $perso->getInventaire($db, $i++))
			if($item instanceof Member_ItemDrogueSubstance)
				$items[count($items)] = $item;
		$tpl->set('INV_PERSO', $items);
		
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/laboDrogue.htm',__FILE__,__LINE__);
	}
}
?>