<?php
/** Gestion de l'interface d'une banque
*
* @package Member_Action
*/
class Member_Action_Banque{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		//Cout en PA des actions
		$coutPa = 1;
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		
		$query = 'SELECT *
		           FROM ' . DB_PREFIX . 'banque
		           WHERE banque_lieu="' . $perso->getLieu()->getNomTech() . '";';
		$result=$db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)==0)
			return fctErrorMSG('Cette banque est actuellement innaccessible ou innexistante (' . $perso->getLieu()->getNomTech() . ').');
		
		$arr = mysql_fetch_assoc($result);
		$banque = new Member_Banque($arr);
		$tpl->set('BANQUE', $banque);
		
		
		
		
		
		
		if (isset($_POST['save'])) {
			
			if($perso->getPa() < 1)
				return fctErrorMSG('Vous n\'avez pas assez de PA pour effectuer cette action.');
			
			
			//### Ouvrir un compte

				
				
				
				//Instancier le compte afin d'y faire des opérations.
				$query = 'SELECT *
							FROM ' . DB_PREFIX . 'banque_comptes
							WHERE	compte_id=' . $_POST['id'] . '
								AND compte_idperso=' . $perso->getId() . ';';
				$result = $db->query($query,__FILE__,__LINE__);
				$arr=mysql_fetch_assoc($result);
				$compte = $banque->getCompte($arr['compte_compte'], $db, $arr);
				
				
				
					
					
					
					
				//###Fermer un compte
				if(isset($_POST['close'])) {
				
					if (!isset($_POST['c_check']))
						return fctErrorMSG('Vous devez cocher la case pour confirmer que vous voulez bien fermer ce compte.','?popup=1&m=Action_Banque');
					
					//Effectuer le transfert d'argent (Transférer l'argent du compte vers le perso)
					$perso->changeCash('+', $compte->getCash());
					$perso->setCash($db);
					
					//Effacer le compte
					$query = 'DELETE FROM ' . DB_PREFIX . 'banque_comptes
								WHERE	compte_id=' . $_POST['id'] . '
									AND compte_idperso=' . $perso->getId() . '
								LIMIT 1;';
					$db->query($query,__FILE__,__LINE__);
					
					//Retirer les PA
					$perso->changePa('-', 1);
					$perso->setPa();
					
					//Ajouter la transaction à l'historique
					$compte->add_bq_hist($db,'','FRMT', $compte->getCash(), 0, "FERMÉ");
					Member_He::add($db, 0, $perso->getId(), 'banque', 'Vous fermez un compte en banque.');
					
				}else{
					fctBugReport($db, 'Action bancaise innexistante', array($_POST, $perso), __FILE__, __LINE__);
				}
			
		}
		
		
		
		
		
		
		
		//Trouver tout les comptes appartenant au perso
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_banque="' . $banque->getNoBanque() . '"
						AND compte_idperso=' . $perso->getId() . ';';
		$result = $db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result) == 0 || isset($_POST['newaccount'])) {
		
			//Ouvrir un compte (car aucun compte actuellement)
			$tpl->set('BANK_ACCOUNT_NAME',	$perso->getNom());
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_newAccount.htm',__FILE__,__LINE__);
			
		}else{
		
			//Faire les opération sur le(s) compte(s) existant(s).
			$i=0;
			while ($arr = mysql_fetch_assoc($result))
				$BANK_ACCOUNTS[$i++] = $banque->getCompte($arr['compte_compte'], $db, $arr);
			
			$tpl->set('BANK_ACCOUNTS',	$BANK_ACCOUNTS);
			$tpl->set('PERSO_CASH',		$perso->getCash());
			
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque.htm',__FILE__,__LINE__);
		}
	}
}
?>