<?php
/** Gestion de l'interface d'un guichet automatique: Afficher le clavier pour composer le NIP
*
* @package Member_Action
*/
class Member_Action_Guichet2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
			
		//Vérifier si une carte à été sélectionnée
		if (!isset($_POST['carteid']))
			return fctErrorMSG('Aucune carte sélectionnée.', '?popup=1&m=Action_Guichet');
			
		
		$tpl->set('CARD_ID',$_POST['carteid']);
		
		
		//ToDo: Vérifier si le # de carte est valide
		$query = 'SELECT * 
					FROM ' . DB_PREFIX . 'banque_cartes
					WHERE carte_id = ' . $_POST['carteid'] . '
					LIMIT 1;';
		$result = $db->query($query, __FILE__, __LINE__);
		if (mysql_num_rows($result)==0)
			return fctErrorMSG('Cette carte à été désactivée.', '?popup=1&m=Action_Guichet');
		
		
		
		//Afficher le clavier numérique pour composer le NIP
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/guichet2.htm',__FILE__,__LINE__);
		
	}
}
?>
