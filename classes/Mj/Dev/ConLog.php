<?php

/** Visualisation des erreurs répertoriées
 *
 * @package Mj_Dev
 */
 
 class Mj_Dev_ConLog
 {
	public static function generatePage(&$tpl, &$session, &$account, &$mj)
	{
             
		if(!$mj->accessDev())
			return fctErrorMSG('Vous n\'avez pas acc�s � cette page.');
	
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante
		
		//Récupérer les erreurs dans la base de donn�e
		$query = 'SELECT `user`, `date`, `ip` FROM `' . DB_PREFIX . 'log_conn` ORDER BY `date` DESC LIMIT 50';
		$prep = $db->prepare($query);
		$prep->execute($db, __FILE__,__LINE__);
		$result = $prep->fetchAll();
		$prep->closeCursor();
		$prep = NULL;
		
		if(count($result) > 0)
			$tpl->set("CONS",$result);
			
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . '/html/Mj/Dev/ConLog.htm');
	}
 }