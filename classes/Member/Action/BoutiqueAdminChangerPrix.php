<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_BoutiqueAdminChangerPrix{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Définir les accès d'administration
		if($perso->getLieu()->getProprioid() != $perso->getId())
			return fctErrorMSG('Vous devez être propriétaire du lieu pour pouvoir l\'administrer.', '?popup=1&m=Action_Boutique', array('perso' => $perso, 'lieu' => $lieu));

		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
			
		
		//LISTER TOUT LES ITEMS EN VENTE DANS LA BOUTIQUE
		$i=0;
		while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++)){
			$btvnt = str_replace(",",".",$_POST['btvnt_' . $item->getInvId()]);
			$btach = str_replace(",",".",$_POST['btach_' . $item->getInvId()]);
			$btvnt = (is_numeric($btvnt)) ? $btvnt : -1;
			$btach = (is_numeric($btach)) ? $btach : -1;
			
			$query = "UPDATE " . DB_PREFIX . "item_inv
						SET `inv_boutiquePrixVente`=" . $btvnt . ",
							`inv_boutiquePrixAchat`=" . $btach . "
						WHERE inv_id=" . $item->getInvId() . ";";
			$db->query($query,__FILE__,__LINE__);
		}
		
		$tpl->set('PAGE', 'Action_BoutiqueAdmin&popup=1');
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/redirect.htm',__FILE__,__LINE__);
	}
}
?>