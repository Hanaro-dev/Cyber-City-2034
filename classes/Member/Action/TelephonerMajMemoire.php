<?php
/** Gestion de l'interface de l'action mettre a jour et gestion du répertoire téléphonique de ce téléphone: Afficher l'interface de l'action
*
* @package Member_Action
*/
class Member_Action_TelephonerMajMemoire{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$idTel = $_POST['telephone'];
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(($item->getInvId() == $idTel) AND ($item->getIdProprio() == $perso->getId())){
				$telephone = $item;
				}
			}
			
	if($telephone->getMemory() != ""){
		$contenuMemoire = explode( ",",$telephone->getMemory());	
	}else{
		$contenuMemoire = array("","");
	}

	$tpl->set('telephone', $telephone);
	$tpl->set('contenuMemoire', $contenuMemoire);
	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/telephonerMajMemoire.htm',__FILE__,__LINE__);
	}
}
?>