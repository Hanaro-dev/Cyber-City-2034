<?php
/** Soigner un personnage (blessures superficielles): But générer un template des personnes blessées léger à soigner
*
* @package Member_Action
*/
class Member_Action_PersoSoigner {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{

		$i=0;$e=0;
		$persoSoignable = array();
		while($arrPerso = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if(($arrPerso->getPv() < $arrPerso->getPvMax()))
			{
				$persoSoignable[$e++]['perso'] = $arrPerso;
			//Info TXT sur l'état du perso
			if ($arrPerso->isNormal())
				$persoSoignable[$e-1]['etat'] = "En santé";
			elseif ($arrPerso->isAutonome())
				$persoSoignable[$e-1]['etat'] = "Légèrement blessé";
			elseif($arrPerso->isConscient())
				$persoSoignable[$e-1]['etat'] = "Blessé gravement";
			elseif($arrPerso->isVivant())
				$persoSoignable[$e-1]['etat'] = "Inconscient";
			else
				$persoSoignable[$e-1]['etat'] = "Mort";
			}
		}
		
		$i=0; $e=0;
		$trousses=array();
		while( $item = $perso->getInventaire($db, $i++)){
			if(is_a($item, 'Member_ItemTrousse')){
				if($item->getResistance() > 0)
					$trousses[$e++] = $item;
			}	
		}		

	$tpl->set('PERSO_SOIGNABLE', $persoSoignable);
	$tpl->set('TROUSSES', $trousses);
	
	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/persoSoigner.htm',__FILE__,__LINE__);	
	}
}

?>