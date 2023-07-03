<?php
/** Gestion des logs sur la suppression des perso
*
* @package Mj
*/
class Mj_Perso_Log{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$mj)
	{	//BUT: Démarrer un template propre à cette page
	
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'log_persosuppr
					ORDER BY id;';
		$result = $db->query($query,__FILE__,__LINE__);
		$i=0;
		$donnees = array();
		while ($arr = mysql_fetch_assoc($result)){
			$donnees[$i] = $arr;
			$donnees[$i]['timestamp'] = date('d/m/Y', $arr['timestamp']);
			$i++;
		}
		
		$tpl->set("donnees",$donnees);
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . '/html/Mj/Perso/Log.htm'); 
	}
}


?>