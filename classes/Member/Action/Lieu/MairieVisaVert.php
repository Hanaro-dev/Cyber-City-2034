<?php
/** Gestion de l'interface d'un guichet automatique: Sélectionner une carte
*
* @package Member_Action
*/
class Member_Action_Lieu_MairieVisaVert
{
	public static function generatePage(&$tpl, &$session, &$account, &$perso)
	{
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante
		
	
		//Vérifier l'état du perso
		if(!$perso->isNormal())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		/*	
		//Vérifier si le perso possède déjà son visa vert
		if($perso->getVisaPerm()=="1")
			return fctErrorMSG("Vous avez déjà votre Visa Vert.");

		//Vérifier l'état du perso
		if(!$perso->isNormal())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		//Vérifier si une demande d'examen à été recue
		if(!isset($_POST['Exam']))
			return fctErrorMSG('Merci de passer par le formulaire.');
		
		
		//Vérifier si le perso a suffisamment d'argent sur lui
		if($perso->getCash() < $coutCash)
			return fctErrorMSG('Vous n\'avez pas assez d\'argent pour effectuer cette action.');
		
		
		//Valider si le perso a suffisamment de PA
		if($perso->getPa() <= $coutPa)
			return fctErrorMSG('Vous n\'avez pas assez de PA pour effectuer cette action.');
		*/
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/Lieu/MairieVisaVert.htm',__FILE__,__LINE__);
	}
}
