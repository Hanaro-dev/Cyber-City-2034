<?php
/** Gestion des options d'affichage
*
* @package Member
*/
class Member_Config_Affichage2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		$query = 'UPDATE ' . DB_PREFIX . 'account
					SET skin="' . $_POST['skinname'] . '",
						heitems="' . $_POST['heitems'] . '"
						WHERE id=' . $account->getId() . ';';
		$db->query($query,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>