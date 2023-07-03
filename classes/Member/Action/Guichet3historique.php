<?php
/** Gestion de l'interface d'un guichet automatique: Afficher l'historique des transactions
*
* @package Member_Action
*/
class Member_Action_Guichet3historique{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
	
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
			
		//Vérifier si une carte à été sélectionnée
		if (!isset($_POST['carteid']))
			return fctErrorMSG('Aucune carte sélectionnée.', '?popup=1&m=Action_Guichet');
		$tpl->set('CARD_ID',	$_POST['carteid']	);
		
		
		if (!isset($_POST['nip']))
			return fctErrorMSG('Aucun NIP spécifiée.', '?popup=1&m=Action_Guichet2', array('carteid' => $_POST['carteid']));
		$tpl->set('NIP',		$_POST['nip']		);
		
		
		//Créer la carte + compte
		$query = 'SELECT * 
					FROM ' . DB_PREFIX . 'banque_cartes
					LEFT JOIN ' . DB_PREFIX . 'banque_comptes ON (compte_banque = carte_banque AND compte_compte = carte_compte)
					WHERE carte_id = ' . $_POST['carteid'] . '
					LIMIT 1;';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			fctBugReport(
				$db, 
				'Cette carte n\'existe pas',
				array(
					'perso' => $perso,
					'query' => $query
				),
				__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__
			);
		$arr = mysql_fetch_assoc($result);
		
		$compte = new Member_Banquecompte($arr);
		$carte = $compte->getCarte($_POST['carteid'], $db, $arr);
		$tpl->set('COMPTE', $compte->getNoBanque() . '-' . $compte->getNoCompte());
		
		
		//Valider si la carte est active ou non
		if(!$carte->isValid())
			return fctErrorMSG('Cette carte à été désactivée.', '?popup=1&m=Action_Guichet');
		
		
		//Valider le NIP
		if($carte->getNip() != $_POST['nip'])
			return fctErrorMSG('NIP invalide.', '?popup=1&m=Action_Guichet2', array('carteid' => $_POST['carteid']));
		
		
		//Charger l'historique des transactions
		$query = 'SELECT * 
					FROM ' . DB_PREFIX . 'banque_historique
					WHERE compte="' . $compte->getNoBanque() . '-' . $compte->getNoCompte() . '";';
		$result = $db->query($query, __FILE__, __LINE__);
		$historique = array();
		$i=0;
		while($arr = mysql_fetch_assoc($result)){
			$arr['date']	= fctToGameTime($arr['date']);
			$arr['retrait'] = fctCreditFormat($arr['retrait'], true);
			$arr['depot']	= fctCreditFormat($arr['depot'], true);
			$arr['solde']	= fctCreditFormat($arr['solde'], true);
			$historique[$i++] = $arr;
		}
		
		$tpl->set('HISTORIQUE',		$historique		);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique.htm',__FILE__,__LINE__);
	}
}
?>
