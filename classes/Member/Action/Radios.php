<?php
/** Gestion des radio: Affichag edes radios dispo
*
* @package Member_Action
*/
class Member_Action_Radios {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemRadio')){
				$radios[$e++] = $item;
				}
			}
		
	
	
	$tpl->set('LIST_RADIOS', $radios);
	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/radios.htm',__FILE__,__LINE__);	
	}
}

?>