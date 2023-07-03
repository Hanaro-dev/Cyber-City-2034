<?php
/** Gestion des radio: Regler la clef de cryptage de la radio
*
* @package Member_Action
*/
class Member_Action_RadioMajClef2 {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemRadio') && ($item->getInvId() == $_POST['idradio'])){
				$radio = $item;
				}
			}
		
		if(!is_numeric($_POST['clef'])){return fctErrorMSG('Cette clef n\'est pas numérique', '?popup=1&m=Action_RadioMajClef');}	
		
		echo $radio->majClef($db,$_POST['clef']);

	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/radioMajClef2.htm',__FILE__,__LINE__);	
	}
}