<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_FouillerLieu{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		
		//LISTER TOUT LES ITEMS EN VENTE DANS LA BOUTIQUE
		$i=0; $items=array();
		while( $item = $perso->getLieu()->getItems($db, $i++))
			$items[$i++] = $item;
		$tpl->set('INV_LIEU', $items);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/fouillerLieu.htm',__FILE__,__LINE__);
	}
}
?>