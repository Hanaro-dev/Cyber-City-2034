<?php
/** Effectuer un retrait bancaire ( AJAX ONLY )
*
* @package Member_Action
*/
class Member_Action_BanqueTransfert{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		$coutPa = 1;
		
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			die('00|' . rawurlencode('Votre n\'êtes pas en état d\'effectuer cette action.'));
		
		//Vérifier les PA du perso
		if($perso->getPa() < $coutPa)
			die('01|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		//Valider le # de compte ( TODO: CRÉER UN REGEX !!!! )
		if (strlen($_POST['compte'] )!=19) // Créer un REGEX
			die('05|' . rawurlencode('Le # de compte doit être au format XXXX-XXXX-XXXX-XXXX.'));
		//Décomposer le # de compte vers lequel faire le transfert
		$cptno = explode('-',$_POST['compte']);
		if (count($cptno)!=4)  // Créer un REGEX
			die('06|' . rawurlencode('Le # de compte doit être au format XXXX-XXXX-XXXX-XXXX.'));
		
		
		//Instancier la banque
		$query = 'SELECT *
		           FROM ' . DB_PREFIX . 'banque
		           WHERE banque_lieu="' . $perso->getLieu()->getNomTech() . '";';
		$result=$db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)==0)
			die('02|' . rawurlencode('Cette banque n\'existe pas (' . $perso->getLieu()->getNomTech() . ').'));
		$arr = mysql_fetch_assoc($result);
		$banque = new Member_Banque($arr);
		
		
		//Instancier le compte
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_id=' . $_POST['id'] . '
						AND compte_idperso=' . $perso->getId() . ';';
		$result = $db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)==0)
			die('03|' . rawurlencode('Ce compte n\'existe pas (' . $_POST['id'] . ').'));
		$arr=mysql_fetch_assoc($result);
		$compte = $banque->getCompte($arr['compte_compte'], $db, $arr);
		
		
		//Instancier le compte distant (celui vers lequel faire le transfert)
		$cptno = explode('-',$_POST['compte']);
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_banque="' . $cptno[0] . '"
						AND compte_compte="' . $cptno[1] . '-' . $cptno[2] . '-' . $cptno[3] . '";';
		$result = $db->query($query,__FILE__,__LINE__);
		$arr=mysql_fetch_assoc($result);
		$compte2 = $banque->getCompte($arr['compte_compte'], $db, $arr);
		
		
		//Valider si le montant est possible
		$_POST['montant'] = str_replace(',','.',$_POST['montant']);
		if ($_POST['montant']<=0 && $_POST['montant']>$compte->getCash())
			die('04|' . rawurlencode('Vous ne pouvez pas retirer plus que vous avez ou un montant vide.'));
		
		//Effectuer le transfert d'argent
		$compte->changeCash('-', $_POST['montant']);
		$compte->setCash($db);
		
		$compte2->changeCash('+', $_POST['montant']);
		$compte2->setCash($db);
		
		//Retirer les PA
		$perso->changePa('-', $coutPa);
		$perso->setPa($db);
		
		//Ajouter la transaction à l'historique
		$compte->add_bq_hist($db, $compte2->getNoBanque() . '-' . $compte2->getNoCompte(), 'STRF', $_POST['montant'], 0);
		$compte2->add_bq_hist($db, $compte->getNoBanque() . '-' . $compte->getNoCompte(), 'RTRF', 0 ,$_POST['montant']);
		
		//Confirmer les modifications avec les informations sur les changements
		die($_POST['id'] . '|OK|' . $compte->getCash() . '|' . $perso->getCash() . '|' . $perso->getPa());
	}
}
?>