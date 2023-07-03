<?php
/** Gestion de l'interface d'un guichet automatique: Affichage du paneau de contrôle (solde, retrait, etc)
*
* @package Member_Action
*/
class Member_Action_Guichet3{
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
			return fctErrorMSG('Aucun NIP spécifié.', '?popup=1&m=Action_Guichet2', array('carteid' => $_POST['carteid']));
		$tpl->set('NIP',		$_POST['nip']		);
		
		
		//Créer la carte + compte
		$query = 'SELECT * 
					FROM ' . DB_PREFIX . 'banque_cartes
					LEFT JOIN ' . DB_PREFIX . 'banque_comptes ON (compte_banque = carte_banque AND compte_compte = carte_compte)
					WHERE carte_id = ' . $_POST['carteid'] . '
					LIMIT 1;';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			return fctErrorMSG('Cette carte à été désactivée.', '?popup=1&m=Action_Guichet');
			
		$arr = mysql_fetch_assoc($result);
		$compte = new Member_Banquecompte($arr);
		$carte = $compte->getCarte($_POST['carteid'], $db, $arr);
		
		
		
		//Valider si la carte est active ou non
		if(!$carte->isValid())
			return fctErrorMSG('Cette carte est actuellement désactivée.', '?popup=1&m=Action_Guichet');
		
		
		//Valider le NIP et afficher la bonne page selon s'il est valide ou non
		if($carte->getNip() == $_POST['nip']){
			//NIP valide, afficher la page
			$tpl->set('SOLDE',fctCreditFormat($compte->getCash(), true));
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/guichet3.htm',__FILE__,__LINE__);
		}else{
			//NIP invalide, afficher à nouveau le clavier
			$tpl->set('PAGE_WRONGACCESS',true);
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/guichet2.htm',__FILE__,__LINE__);
		
		}
	}
}
?>
