<?php

/** Gestion de l'interface de l'action Ordinateur dans le menu item

*

* @package Member_Action

*/

class Member_Action_OrdinateurItemMenu{

	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)

	{	
	//Récupérer les items de type ordinateur que possède le PJ
	$i=0; $e=0;
	while( $item = $perso->getInventaire($db, $i++)){

		if(is_a($item, 'Member_ItemOrdinateur')){
			$ordinateurs[$e++] = $item;
			}
		}
		
		$tpl->set('LIST_PC', $ordinateurs);

		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/ordinateurItemMenu.htm',__FILE__,__LINE__);

	}

}

?>