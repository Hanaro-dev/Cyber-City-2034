<?php
/** Gestion des cartes associés à un compte
*
* @package Member_Action
*/
class Member_Action_BanqueCarteAdd2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		$cout_carte=10;
		
		//Vérifier les paramêtres requis
		if(!isset($_POST['compte']))
			return fctErrorMSG('Ce compte est invalide (aucun compte).');
		
		
		//Valider le # du compte (TODO: REGEX !!!!)
		if(strlen($_POST['compte'])!=19)
			return fctErrorMSG('Ce compte est invalide (no invalide).');
		
		$banque_no = substr($_POST["compte"],0,4);
		$compte_no = substr($_POST["compte"],5,14);
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
		
		
		if (!is_numeric($_POST['nip']))
			return fctErrorMSG('Vous devez entrez un NIP composé uniquement de chiffres de 0 à 9.');
		
		
		//Retirer le cout de la carte du compte
		$compte->changeCash('-', $cout_carte);
		$compte->setCash($db);
		
		
		
		//Créer l'access
		$query = "INSERT INTO " . DB_PREFIX . "banque_cartes
					(`carte_banque`,`carte_compte`,`carte_nom`,`carte_nip`,`carte_valid`)
					VALUES (
						'" . $compte->getNoBanque() . "',
						'" . $compte->getNoCompte() . "',
						'" . addslashes($_POST['nom']) . "',
						" . $_POST['nip'] . ",
						'" . $_POST['valid'] . "'
					);";
		$db->query($query,__FILE__,__LINE__);
		
		
		//Créer l'item carte de guichet
		$query = "INSERT INTO " . DB_PREFIX . "item_inv
					(`inv_dbid`,`inv_persoid`,`inv_nobanque`,`inv_nocompte`,`inv_nocarte`,`inv_qte`,`inv_param`)
					VALUES (
							3,
							" . $perso->getId() . ",
							'" . $compte->getNoBanque() . "',
							'" . $compte->getNoCompte() . "',
							" . mysql_insert_id($db->getConnectionId()) . ",
							1,
							'" . addslashes($_POST['nom']) . "'
					);";
		$db->query($query,__FILE__,__LINE__);
		
		$compte->add_bq_hist($db, '', 'CGCH', $cout_carte, 0);
		Member_He::add($db, 0, $perso->getId(), 'banque', 'Vous obtenez une carte pour le compte ' . $compte->getNoBanque() . '-' . $compte->getNoCompte() . '.');
		
		
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_carte_redirect.htm',__FILE__,__LINE__);
	}
}
?>