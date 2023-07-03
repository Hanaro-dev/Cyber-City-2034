<?php
/** Gestion de la création d'un compte de banque
*
* @package Member_Action
*/
class Member_Action_BanqueCompteAdd{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//Instancier la banque
		$query = 'SELECT *
		           FROM ' . DB_PREFIX . 'banque
		           WHERE banque_lieu="' . $perso->getLieu()->getNomTech() . '";';
		$result=$db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)==0)
			return fctErrorMSG('Cette banque n\'existe pas (' . $perso->getLieu()->getNomTech() . ').');
		$arr = mysql_fetch_assoc($result);
		$banque = new Member_Banque($arr);
		
		
		$tpl->set('BANQUE', $banque);
		$tpl->set('PERSO', $perso);
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_compte_add.htm',__FILE__,__LINE__);
	}
}
?>