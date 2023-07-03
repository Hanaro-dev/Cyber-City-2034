<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_BoutiqueAdminPersoVersBoutique{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
			
		//Définir les accès d'administration
		if($perso->getLieu()->getProprioid() != $perso->getId())
			return fctErrorMSG('Vous devez être propriétaire du lieu pour pouvoir l\'administrer.', '?popup=1&m=Action_Boutique');
		
		//Valider si un item de l'inventaire du perso est sélectionné
		if (!isset($_POST['perso']))
			return fctErrorMSG('Vous devez sélectionner un item dans votre inventaire.', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		
		//Trouver les informations concernant l'item du perso
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){
			if($item->getInvId() == $_POST['perso']){
				$ITEM  = $item;
				break;
			}
		}
		if (!isset($ITEM))
			return fctErrorMSG('L\'item sélectionné n\'existe pas (ou ne vous appartiend pas).', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		
		//Vérifier si la quantité d'item à transféré n'est pas supérieur à ce que le perso posède
		$qte_a_transferer = $_POST['psqte_' . $ITEM->getInvId()];
		if ($qte_a_transferer > $ITEM->getQte())
			return fctErrorMSG('Vous ne pouvez pas transférer plus que le personnage possède.', '?popup=1&m=Action_BoutiqueAdmin');
		
		
		
		
		Member_Item::transfererItemVersBoutique($db, $ITEM, $perso->getLieu(), $qte_a_transferer);
		$perso->refreshInventaire($db);
		
		
		$tpl->set('PAGE', 'Action_BoutiqueAdmin&popup=1');
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/redirect.htm',__FILE__,__LINE__);
	}
}
?>