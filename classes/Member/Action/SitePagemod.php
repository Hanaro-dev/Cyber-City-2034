<?php
/** Gestion de l'action de jeter un item. Cette page est utilisée UNIQUEMENT par AJAX. des # d'erreur sont retourné, pas des message. Aucune interface graphique.
*
* @package Member_Action
*/
class Member_Action_Sitepagemod{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		$actionPa = 1;
		//$cout_ouverture = 5;
		
		
		
		//TODO: Valider si le lieu donne accès à l'ordinateur + Internet
		
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			die('01|' . rawurlencode('Votre n\'êtes pas en état d\'effectuer cette action.'));
		
		if($perso->getPa() < $actionPa)
			die('02|' . rawurlencode('Vous n\'avez pas assez de PA pour effectuer cette action.'));
		
		
		//if(!preg_match('/^([0-9]{4})-([0-9]{4}-[0-9]{4}-[0-9]{4})-([0-9]+)$/', $_POST['no'], $matches))
		//	die('04|' . rawurlencode('Le # de la carte est invalide.'));
		
		//$carte_banque = $matches[1];
		//$carte_compte = $matches[2];
		//$carte_id = $matches[3];
		
		//$query = 'SELECT * 
		//	FROM ' . DB_PREFIX . 'banque_cartes
		//	WHERE 	carte_banque= ' . $carte_banque . '
		//		AND carte_compte="' . $carte_compte . '"
		//		AND carte_id = ' . $carte_id . '
		//	LIMIT 1;';
		//$result = $db->query($query, __FILE__, __LINE__);
		//if (mysql_num_rows($result)==0)
		//	die('05|' . rawurlencode('Cette carte est innexistante.'));
		
		//$carte = mysql_fetch_assoc($result);
		
		//if($carte['carte_valid']==0)
		//	die('06|' . rawurlencode('Cette carte a été désactivée.'));
		
		//if($carte['carte_nip'] != $_POST['nip'])
		//	die('07|' . rawurlencode('NIP erroné.'));
		
		
		//Valider le montant du compte associé à la carte
		//$query = 'SELECT *
		//			FROM ' . DB_PREFIX . 'banque_comptes
		//			WHERE	compte_banque= ' . $carte_banque . '
		//				AND compte_compte="' . $carte_compte . '";';
		//$result = $db->query($query, __FILE__, __LINE__);
		//if (mysql_num_rows($result)==0)
		//	die('08|' . rawurlencode('Le compte associé à la carte a été fermé.'));
		
		//$arr = mysql_fetch_assoc($result);
		//$compte = new Member_Banquecompte($arr);
		
		//if ($compte->getCash() < $cout_ouverture)
		//	die('09|' . rawurlencode('Compte sans fond.'));
		
		if(!is_numeric($_POST['no']))
			die('13|Id de la page invalide');
		
		//Valider si la personne a le droit de modifier la page
		$query = 'SELECT a.modifier, a.admin, p.*, pa.id as paid, w.url
					FROM ' . DB_PREFIX . 'sitesweb_pages as p
					LEFT JOIN ' . DB_PREFIX . 'sitesweb_acces as a ON (a.site_id=p.site_id)
					LEFT JOIN ' . DB_PREFIX . 'sitesweb_pages_acces as pa ON (pa.page_id = p.id AND user_id = a.id)
					LEFT JOIN ' . DB_PREFIX . 'sitesweb as w ON (w.id = a.site_id)
					WHERE	p.id = ' . $_POST['no'] . '
						AND a.user="' . addslashes($_POST['user']) . '"
						AND a.pass="' . addslashes($_POST['pass']) . '"
					LIMIT 1;';
		$result = $db->query($query,__FILE__,__LINE__);
		if(mysql_num_rows($result)==0)
			die('10|' . rawurlencode('Cette URL n\'existe pas.'));
		
		$arr= mysql_fetch_assoc($result);
		if($arr['modifier']=='0' && $arr['admin']=='0')
			die('11|' . rawurlencode('Vous ne possèdez pas les autorisations nécésaires (1).'));
		
		if($arr['admin']=='0' && $arr['acces']=='priv' && empty($arr['paid']))
			die('12|' . rawurlencode('Vous ne possèdez pas les autorisations nécésaires (2).'));
			
			
		
		
		//Tout est ok, Créer la page !!!!! 
		
		$perso->changePa('-', $actionPa);
		$perso->setPa($db);
		//$compte->changeCash('-', $cout_ouverture);
		//$compte->setCash($db);
		//$compte->add_bq_hist($db, '', 'SDPD', $prixTotal);
		
		
		$query = 'UPDATE ' . DB_PREFIX . 'sitesweb_pages
					SET	`titre`		="' . addslashes($_POST['titre']) . '",
						`content`	="' . addslashes($_POST['content']) . '",
						`acces`		="' . (($_POST['acces']=='true') ? 'pub' : 'priv' ) . '",
						`showIndex`	="' . (($_POST['showIndex']=='true') ? '1' : '0' ) . '"
					WHERE id=' . addslashes($_POST['no']) . ';';
		$db->query($query,__FILE__,__LINE__);
		
		
		
		
		
		
		
		
		die('OK|' . $perso->getPa() . '|' . $arr['url'] . '/' . $_POST['no']); //Tout est OK
	}
}
?>