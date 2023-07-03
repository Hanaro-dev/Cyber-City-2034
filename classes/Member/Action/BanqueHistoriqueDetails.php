<?php
/** Gestion de l'interface d'un guichet automatique: Afficher les détails sur une transaction
*
* @package Member_Action
*/
class Member_Action_BanqueHistoriqueDetails{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
	
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
			
		//Vérifier les paramêtres requis
		if(!isset($_POST['compte']))
			return fctErrorMSG('Ce compte est invalide (aucun compte).');
		
		if(!isset($_POST['trsid']))
			return fctErrorMSG('Cette transaction est invalide (aucune transaction).');
			
			
		//Valider le # du compte (TODO: REGEX !!!!)
		if(strlen($_POST['compte'])!=19)
			return fctErrorMSG('Ce compte est invalide (no invalide).');
			
		
		$banque_no = substr($_POST['compte'],0,4);
		$compte_no = substr($_POST['compte'],5,14);
		$tpl->set('COMPTE', $_POST['compte']);
		
		
		//Instancier le compte afin d'y faire des opérations.
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_banque="' . $banque_no . '"
						AND compte_compte="' . $compte_no . '";';
		$result = $db->query($query,__FILE__,__LINE__);
		$arr=mysql_fetch_assoc($result);
		$compte = new Member_BanqueCompte($arr);
		
		
		//Vérifier si le compte appartiend bien au perso
		if ($compte->getIdPerso() != $perso->getId())
			return fctErrorMSG('Ce compte ne vous appartiend pas.');
		
			
			
		//Charger l'historique des transactions
		$query = 'SELECT * 
					FROM ' . DB_PREFIX . 'banque_historique
					WHERE compte="' . $compte->getNoBanque() . '-' . $compte->getNoCompte() . '"
						AND id=' . $_POST['trsid'] . ';';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			return fctErrorMSG('Cette transaction est invalide (transaction innexistante).');
		$arr = mysql_fetch_assoc($result);
		$arr['date']	= fctToGameTime($arr['date'], true);
		$arr['solde']	= fctCreditFormat($arr['solde'], true);
		
		
		
		
		switch($arr['code']){
			case 'RETR':
				$arr['montant'] = fctCreditFormat($arr['retrait']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailsdirect.htm',__FILE__,__LINE__);
				break;
			case 'DPOT':
				$arr['montant'] = fctCreditFormat($arr['depot']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailsdirect.htm',__FILE__,__LINE__);
				break;
			case 'RTRF':
				$arr['montant'] = fctCreditFormat($arr['depot']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailstransfert.htm',__FILE__,__LINE__);
				break;
			case 'STRF':
				$arr['montant'] = fctCreditFormat($arr['retrait']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailstransfert.htm',__FILE__,__LINE__);
				break;
			default:
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailsnodetails.htm',__FILE__,__LINE__);
				break;
		}
	}
}