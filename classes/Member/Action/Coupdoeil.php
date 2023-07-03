<?php
/** Gestion de l'interface de Coup d'oeil au lieu actuel
*
* @package Member_Action
*/
class Member_Action_Coupdoeil{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		//Vérifier l'état du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		
		//Générer les informations sur le lieu actuel
		$tpl->set('LIEU_NOM',	$perso->getLieu()->getNom());
		$tpl->set('LIEU_DESC',	nl2br($perso->getLieu()->getDescription()));
		$tpl->set('LIEU_IMG', 	$perso->getLieu()->getImage());
		$tpl->set('id', 		$perso->getId());	//Afin d'éviter de s'auto-renommer.
		
		//Générer la liste des personnages présent dans le lieu actuel
		$i=0; $e=0;
		$arrPersos = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++)){
		
			//Info du perso
			$arrPersos[$e]['perso'] = $tmp;
			
			//Info sur l'arme (Mains nues si aucune)
			$arrPersos[$e]['arme'] = $tmp->getArme($db)->getNom();
			
			//Info TXT sur l'état du perso
			if ($tmp->isNormal())
				$arrPersos[$e]['etat'] = "En santé";
			elseif ($tmp->isAutonome())
				$arrPersos[$e]['etat'] = "Légèrement blessé";
			elseif($tmp->isConscient())
				$arrPersos[$e]['etat'] = "Blessé gravement";
			elseif($tmp->isVivant())
				$arrPersos[$e]['etat'] = "Inconscient";
			else
				$arrPersos[$e]['etat'] = "Mort";
				
			$e++;
		}
		$tpl->set('PERSOS', $arrPersos);
		
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/coupdoeil.htm',__FILE__,__LINE__);
	}
}

?>
