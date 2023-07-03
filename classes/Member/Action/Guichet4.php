<?php
/** Gestion de l'interface d'un guichet automatique: Effectuer un retrait
*
* @package Member_Action
*/
class Member_Action_Guichet4{
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
		
		
		
		
		
		
		
		
		
		//Valider le montant d'argent à retirer
		$montant = round($_POST['retrait'],2);
		if(!is_numeric($montant) || $montant<=0)
			return fctErrorMSG(
						'Montant invalide.',
						'?popup=1&m=Action_Guichet3',
						array(
							'carteid' => $_POST['carteid'], 
							'nip' => $_POST['nip']
						)
					);
		
		if ($compte->getCash() < $montant)
			return fctErrorMSG(
						'Tentative de retrait supérieur au montant maximal autorisé.',
						'?popup=1&m=Action_Guichet3',
						array(
							'carteid' => $_POST['carteid'], 
							'nip' => $_POST['nip']
						)
					);
		
		//Retirer l'argent du compte
		$compte->changeCash('-', $montant);
		$compte->setCash($db);
		
		//Ajouter l'argent au perso
		$perso->changeCash('+', $montant);
		$perso->setCash($db);
		
		$perso->changePa('-', 1);
		$perso->setPa($db);
		
		
		//Ajouter le message à l'historique bancaire
		Member_Banquecompte::addHist(
			$db,
			$noCompte,						//Du compte
			'',								//Vers le compte
			'RGUI',							//Type de transaction
			$montant,						//Montant retrait
			0,								//Montant dépot
			($compte->getCash()-$montant)	//Solde
		);
		
		
		
		//Copier le message dans les HE
		Member_He::add($db, '', $perso->getId(), 'parler', "Vous effectuez un retrait de " . fctCreditFormat($montant, true) . " au guichet automatique.");
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>
