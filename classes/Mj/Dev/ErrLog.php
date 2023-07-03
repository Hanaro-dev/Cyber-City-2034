<?php

/** Visualisation des erreurs répertoriées
 *
 * @package Mj_Dev
 */
 
 class Mj_Dev_ErrLog
 {
	public static function generatePage(&$tpl, &$session, &$account, &$mj)
	{
             
		if(!$mj->accessDev())
			return fctErrorMSG('Vous n\'avez pas acc�s � cette page.');
	
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante
		
		//Récupérer les erreurs dans la base de donn�e
		$query = 'SELECT * FROM `' . DB_PREFIX . 'buglog` ORDER BY `date` DESC LIMIT 50';
		$prep = $db->prepare($query);
		$prep->execute($db, __FILE__,__LINE__);
		$result = $prep->fetchAll();
		$prep->closeCursor();
		$prep = NULL;
		
		if(count($result) > 0)
			$tpl->set("ERRORS",$result);
			
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . '/html/Mj/Dev/ErrLog.htm');
	}
 }