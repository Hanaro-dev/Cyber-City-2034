<?php
/** Gestion de l'interface de l'action Parler: Afficher l'interface pour parler.
*
* @package Member_Action
*/
class Member_Action_OrdinateurFixe{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//TODO: Valider si le lieu donne accès à l'ordinateur
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/ordinateur.htm',__FILE__,__LINE__);
	}
}
?>