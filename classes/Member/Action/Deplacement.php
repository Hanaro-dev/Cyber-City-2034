<?php
/** Gestion de l'interface des déplacement
*
* @package Member_Action
*/
class Member_Action_Deplacement{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
			
		
		//Générer la liste des lieux connexes
		$i=0;
		$arrLieux = array();
		while($lien = $perso->getLieu()->getLink($db, $i, $perso->getId()))
			$arrLieux[$i++] = $lien;
		$tpl->set('LIEUX', $arrLieux);
		
		
		//Générer la liste des personnages (à qui nous pourrions tenir la porte)
		$i=0; $e=0;
		$arrPersos = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() != $perso->getId())
				$arrPersos[$e++] = $tmp;
		$tpl->set('PERSOS', $arrPersos);
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/deplacement.htm',__FILE__,__LINE__);
	}
}

?>