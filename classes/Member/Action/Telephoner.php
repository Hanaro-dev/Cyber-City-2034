<?php
/** Gestion de l'interface de l'action Téléphoner: Afficher l'interface de l'action
*
* @package Member_Action
*/
class Member_Action_Telephoner {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
	
		
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemTelephone')){
				$telephonesDuPerso[$e++] = $item;
				}
			}
		
		
	$tpl->set('LIST_TELEPHONES', $telephonesDuPerso);
	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/telephoner.htm',__FILE__,__LINE__);
	}
}