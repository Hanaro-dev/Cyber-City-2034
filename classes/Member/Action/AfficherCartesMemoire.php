<?php
/** Gestion des cartes mémoires: Afficher l'interface de l'action
*
* @package Member_Action
*/
class Member_Action_AfficherCartesMemoire {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemOrdinateur')){
				$ordinateurs[$e++] = $item;
				}
			}
		
		$i=0; $e=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemCarteMemoire')){
				$cm[$e++] = $item;
				}
			}	
	
	
	
	
	$tpl->set('LIST_PC', $ordinateurs);
	$tpl->set('LIST_CM', $cm);
	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/afficherCartesMemoire.htm',__FILE__,__LINE__);	
	}
}

?>