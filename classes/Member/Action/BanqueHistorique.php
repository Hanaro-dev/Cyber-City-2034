<?php
/** Gestion des relever de compte en banque
*
* @package Member_Action
*/
class Member_Action_BanqueHistorique{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
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
		
		
		
		
		
		//Lister toutes les transaction
		$query = 'SELECT *
					FROM ' . DB_PREFIX . 'banque_historique
					WHERE compte="' . $_POST['compte'] . '"
					ORDER BY date ASC;';
		$result = $db->query($query,__FILE__,__LINE__);
		if (mysql_num_rows($result)>0){
			$HISTORIQUE = array();
			$i=0;
			while($arr= mysql_fetch_assoc($result)){
				$HISTORIQUE[$i]['id']		= $arr['id'];
				$HISTORIQUE[$i]['date']		= fctToGameTime($arr['date']); //A quelle heure à été envoyé le message ?
				$HISTORIQUE[$i]['code']		= $arr['code'];
				$HISTORIQUE[$i]['depot']	= ($arr['depot']==0) ? '' : fctCreditFormat($arr['depot']);
				$HISTORIQUE[$i]['retrait']	= ($arr['retrait']==0) ? '' : fctCreditFormat($arr['retrait']);
				$HISTORIQUE[$i]['solde']	= fctCreditFormat($arr['solde']);
				$i++;
			}
			$tpl->set('HISTORIQUE',$HISTORIQUE);
		}
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique.htm',__FILE__,__LINE__);
	}
}