<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Donneritem{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Déclaration des variables pour cette action
		$pacost = 2; //PA par item
		
		//Créer le template
		$tpl->set("PA_COST",$pacost);
		$tpl->set("PR", $perso->getPr());
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
		
		
		//Lister l'inventaire du perso actuel
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++))
			$invPerso[$e++] = $item;
		
		$tpl->set('INV_PERSO', $invPerso);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/donnerItem.htm',__FILE__,__LINE__);
	}
}
?>