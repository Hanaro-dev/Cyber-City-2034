<?php
/** Gestion de la création d'un compte de banque
*
* @package Member_Action
*/
class Member_Action_BanqueCompteAdd2{
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
		
		
		//Vérifier si la personne à assez d'argent sur elle pour ouvrir un compte
		if ($perso->getCash() < $banque->getFraisOuverture ())
			return fctErrorMSG('Vous n\'avez pas assez d\'argent pour effectuer cette action.', '?popup=1&m=Action_BanqueCompteAdd');
		
		//Effectuer le paiement ( Cash + PA)
		$perso->changeCash('-', $banque->getFraisOuverture ());
		$perso->setCash($db);
		
		$perso->changePa('-', 1);
		$perso->setPa($db);
		
		//Créer le compte
		$compte_no = Member_BanqueCompte::generateAccountNo($db);
		$query = 'INSERT INTO `' . DB_PREFIX . 'banque_comptes`
					(`compte_idperso` , `compte_nom` , `compte_banque` , `compte_compte` , `compte_cash`)
					VALUES (
							' . $perso->getId() . ',
							"' . addslashes($_POST['nom']) . '",
							"' . $banque->getNoBanque() . '",
							"' . $compte_no . '",
							"0"
							);';
		$db->query($query,__FILE__,__LINE__);
		
		
		//Instancier le compte afin d'y faire des opérations.
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_comptes
					WHERE	compte_id=' . mysql_insert_id($db->getConnectionId()) . '
						AND compte_idperso=' . $perso->getId() . ';';
		$result = $db->query($query,__FILE__,__LINE__);
		$arr=mysql_fetch_assoc($result);
		$compte = $banque->getCompte($arr['compte_compte'], $db, $arr);
		
		
		//Ajouter l'ouverture à l'historique
		$compte->add_bq_hist($db,'','OVRT',0,0);
		
		$tpl->set('PAGE', 'Action_Banque&popup=1');
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/redirect.htm',__FILE__,__LINE__);
	}
}
?>