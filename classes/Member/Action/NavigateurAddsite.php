<?php
/** Gestion de l'interface pour créer un site sur Domnet
*
* @package Member_Action
*/
class Member_Action_NavigateurAddsite{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/navigateurDomnetAddsite.htm',__FILE__,__LINE__);
	}
}
?>