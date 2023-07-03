<?php
/** Gestion des radio: Créer un template pour gérer la fréquence de la radio
*
* @package Member_Action
*/
class Member_Action_RadioMajFrequence {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemRadio') && ($item->getInvId() == $_POST['idradio'])){
				$radio = $item;
				}
			}
			
		$tpl->set('radio',$radio);

	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/radioMajFrequence.htm',__FILE__,__LINE__);	
	}
}