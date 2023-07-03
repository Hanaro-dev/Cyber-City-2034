<?php
/** Gestion des cartes associés à un compte
*
* @package Member_Action
*/
class Member_Action_BanqueCarteDel{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Vérifier les paramêtres requis
		if(!isset($_POST['compte']))
			return fctErrorMSG('Ce compte est invalide (aucun compte).');
		
		if(!isset($_POST['cid']))
			return fctErrorMSG('Cete carte est invalide (aucune carte).');
		
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
		
		
		$query = "DELETE FROM " . DB_PREFIX . "banque_cartes 
			WHERE	carte_banque='" . $banque_no . "'
				AND carte_compte='" . $compte_no . "'
				AND carte_id=" . $_POST['cid'] . ";";
		$db->query($query,__FILE__,__LINE__);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_carte_redirect.htm',__FILE__,__LINE__);
	}
}
?>