<?php
/** Gestion des radio: Regler la clef de cryptage de la radio
*
* @package Member_Action
*/
class Member_Action_RadioMajClef {
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
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/radioMajClef.htm',__FILE__,__LINE__);	
	}
}