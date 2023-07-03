<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Donnercash{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Déclaration des variables pour cette action
		$pacost = 2; //PA par transaction
		
		//Créer le template
		$tpl->set("PA_COST",$pacost);
		$tpl->set("CASH",$perso->getCash());
		$tpl->set("PA",$perso->getPa());
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		
		// Liste des perso dans le lieu actuel
		$i=0; $e=0;
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() != $perso->getId())
				$persoDansLeLieuActuel[$e++] = $tmp;
		
		$tpl->set('LIST_PERSO', $persoDansLeLieuActuel);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/donnerCash.htm',__FILE__,__LINE__);
	}
}
?>