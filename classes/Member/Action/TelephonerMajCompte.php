<?php
/** Gestion de l'interface de l'action Téléphoner: Envoyer le message
*
* @package Member_Action
*/
class Member_Action_TelephonerMajCompte { 
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$idTel = $_POST['telephone'];
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(($item->getInvId() == $idTel) AND ($item->getIdProprio() == $perso->getId())){
				$telephone = $item;
				}
			}
	
	if($telephone->getNocompte() != ""){
		$numCompte = $telephone->getNobanque()."-".$telephone->getNocompte();
	}
	else{
		$numCompte = "";
	}

	$tpl->set('idTel', $telephone->getInvId());
	$tpl->set('numCompte', $numCompte);

	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/telephonerMajCompte.htm',__FILE__,__LINE__);
	}
}
?>
