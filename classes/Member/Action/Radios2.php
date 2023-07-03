<?php
/** Gestion des radio: Traitement de l'appel  radio
*
* @package Member_Action
*/
class Member_Action_Radios2 {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		if(!isset($_POST['radio']))
			return fctErrorMSG('Aucune radio sélectionnée', '?popup=1&m=Action_Radios');
		if( $_POST['message'] == NULL)
			return fctErrorMSG('Aucun son n\'a été enregistré', '?popup=1&m=Action_Radios');
			
			
		//On récupère la radio associée à l'id transmis
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemRadio') && ($item->getInvId() == $_POST['radio']) ){
				$radio = $item;
				}
			}
		if($radio == null )
			return fctErrorMSG('Aucune radio sélectionnée', '?popup=1&m=Action_Radios');

		
		echo $radio->utiliser($db,$perso,$_POST['message']);
		
	//Rafraichir le HE
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}

?>