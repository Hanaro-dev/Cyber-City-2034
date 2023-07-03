<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Donnercash2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Déclaration des variables pour cette action
		$pacost = 2; //PA par item
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.','?popup=1&m=Action_Donnercash');
		
		
		//Créer le template
		if($perso->getPa()<$pacost)
			return fctErrorMSG('Vous n\'avez pas assez de PA.','?popup=1&m=Action_Donnercash');
		
		//Cash spécifié ?
		if(!isset($_POST['cash']))
			return fctErrorMSG('Aucun montant.','?popup=1&m=Action_Donnercash');
			
		//Vérifier la validité du montant
		if(!is_numeric($_POST['cash']) || $_POST['cash']<1)
			return fctErrorMSG('Montant invalide','?popup=1&m=Action_Donnercash');
		
		if($_POST['cash']>$perso->getCash())
			return fctErrorMSG('Vous ne pouvez pas transférer plus que vous possèdez.','?popup=1&m=Action_Donnercash');
		
		
		
		
		
		//Vérifier si le perso à qui donner l'argent est présent dans le bon lieu
		$found = false;
		$i=0;
		while($toPerso = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if($toPerso->getId() == $_POST['toPersoId']){
				$found=true;
				break;
			}
		}
		if(!$found)
			return fctErrorMSG('Ce personnage n\'est pas dans le lieu ou vous vous trouvez actuellement.','?popup=1&m=Action_Donnercash');
		

		//Tranférer l'item du perso actuel vers l'autre perso.
		$perso->changePa('-', $pacost);
		$perso->setPa($db);
		
		$perso->changeCash('-', $_POST['cash']);
		$perso->setCash($db);
		
		$toPerso->changeCash('+', $_POST['cash']);
		$toPerso->setCash($db);
		
		
		Member_He::add($db, $perso->getId(), $toPerso->getId(), 'donner', "Montant d'argent transféré: " . fctDollarFormat($_POST['cash']));
		
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>