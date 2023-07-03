<?php
/** Gestion de l'interface d'un guichet automatique: Afficher les détails sur une transaction
*
* @package Member_Action
*/
class Member_Action_Guichet3historiquedetails{
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
		
		
		if (!isset($_POST['trsid']))
			return fctErrorMSG(
						'Transaction invalide (1).',
						'?popup=1&m=Action_Guichet3historique',
						array(
							'carteid' => $_POST['carteid'], 
							'nip' => $_POST['nip']
						)
					);
			
			
		
		
		
		
		
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
					WHERE compte="' . $compte->getNoBanque() . '-' . $compte->getNoCompte() . '"
						AND id=' . $_POST['trsid'] . ';';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			return fctErrorMSG(
						'Transaction invalide (2).',
						'?popup=1&m=Action_Guichet3historique',
						array(
							'carteid' => $_POST['carteid'], 
							'nip' => $_POST['nip']
						)
					);
		$arr = mysql_fetch_assoc($result);
		$arr['date'] = fctToGameTime($arr['date'], false, true);
		$arr['solde']	= fctCreditFormat($arr['solde'], true);
		
		
		
	

		switch($arr['code']){
			case "RETR":
				$arr['montant'] = fctCreditFormat($arr['retrait']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailsdirect.htm',__FILE__,__LINE__);
				break;
			case "DPOT":
				$arr['montant'] = fctCreditFormat($arr['depot']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailsdirect.htm',__FILE__,__LINE__);
				break;
			case "RTRF":
				$arr['montant'] = fctCreditFormat($arr['depot']);
				$tpl->set('TRANSACTION',		$arr		);
				return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/banque_historique_detailstransfert.htm',__FILE__,__LINE__);
				break;
			case "STRF":
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