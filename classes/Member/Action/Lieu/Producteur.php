<?php
/** Gestion du travail à la production
*
* @package Member_Action
*/
class Member_Action_Lieu_Producteur
{
	public static function generatePage(&$tpl, &$session, &$account, &$perso)
	{
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante
		
		
		//Valider l'état du perso
		if (!$perso->isNormal())
			return fctErrorMSG('Vous n\'êtes pas en état d\'effectuer cette action.');
		
		if ($perso->getMenotte())
			return fctErrorMSG('Vous ne pouvez pas travailler en étant menotté.');
		
		
		//Trouver les informations sur le producteur
		$query = 'SELECT *'
				. ' FROM `' . DB_PREFIX . 'producteur`'
				. ' WHERE lieuId=:lieuId'
				. ' AND lieuMenuId=:lieuMenuId;';
				//. ' LIMIT 1;';
		$prep = $db->prepare($query);
		$prep->bindValue(':lieuId',		$perso->getLieu()->getId(),		PDO::PARAM_INT);
		$prep->bindValue(':lieuMenuId',		$_GET['modid'],		PDO::PARAM_INT);
		$prep->execute($db, __FILE__, __LINE__);
		$arr = $prep->fetch();
		$prep->closeCursor();
		$prep = NULL;
		
		if($arr === false)
			return fctErrorMSG('Ce lieu n\'est pas un producteur.');
                if(strlen($arr['comp_requise'])!=0)
                {
                    $comp=$perso->convCompCodeToId($arr['comp_requise']);
                    
                    if($perso->getCompRealLevel($comp)<$arr['comp_lvl'])
					{
                        $message = 'Vos compétences ne vous permettent pas de travailler ici (' . $perso-> getCompName($perso->convCompCodeToId($arr['comp_requise'])) . ' ' . $arr['comp_lvl'] . ').';
						
						return fctErrorMSG($message);
					
					}
                }
		
		$tpl->set('PROD', $arr);
		$tpl->set('PERSO', $perso);
		$tpl->set('MODID', $_GET['modid']);
		
		//Retourner le template bâti.
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/Lieu/Producteur.htm',__FILE__,__LINE__);
	}
}


