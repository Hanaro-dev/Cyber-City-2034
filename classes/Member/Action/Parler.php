<?php
/** Gestion de l'interface de l'action Parler: Afficher l'interface pour parler.
*
* @package Member_Action
*/
class Member_Action_Parler{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		$i=0; $e=0;
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() != $perso->getId())
				$persoDansLeLieuActuel[$e++] = $tmp;
		
		$tpl->set('LIST_PERSO', $persoDansLeLieuActuel);
		
		$i=0; $e=0;
		$badgeEnPossessionDuPerso = array();
		while( $item = $perso->getInventaire($db, $i++))
			if(is_a($item, 'Member_ItemBadge'))
				$badgeEnPossessionDuPerso[$e++] = $item;
		
		$tpl->set('LIST_BADGE', $badgeEnPossessionDuPerso);
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/parler.htm',__FILE__,__LINE__);
	}
}
?>