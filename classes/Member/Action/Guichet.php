<?php
/** Gestion de l'interface d'un guichet automatique: Sélectionner une carte
*
* @package Member_Action
*/
class Member_Action_Guichet{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
	
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		//Afficher la liste des cartes de guichet
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++))
			if($item instanceof Member_ItemCartebanque)
				$carteEnPossessionDuPerso[$e++]  = $item;
		if (isset($carteEnPossessionDuPerso))
			$tpl->set('LIST_CARTE', $carteEnPossessionDuPerso);
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/guichet.htm',__FILE__,__LINE__);
	}
}
?>
