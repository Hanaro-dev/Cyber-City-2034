<?php
/** Gestion des interactions de l'action Parler: Effectuer l'action de parler (envoyer le message)
*
* @package Member_Action
*/
function array_remove($arr,$value) {
   return array_values(array_diff($arr,array($value)));
}


class Member_Action_Parler2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		
		//Valider si: Aucun message tappé et on essaie pas de montrer un Badge
		if ((!isset($_POST['msg']) || empty($_POST["msg"])) && (!isset($_POST['badge']) || $_POST['badge']=='0'))
			return fctErrorMSG('Aucun message à envoyer.', '?popup=1&m=Action_Parler');
		
		
		//Charger les informations sur le badge et créer l'entête du message si nécéssaire.
		if (isset($_POST['badge']) && $_POST['badge']!='0'){//Afficher le BADGE
			$badge_txt = '';
			if ($_POST['badge']=='VISAVERT'){
				$badge_txt = 'Une personne vous présente son Visa Vert: ' . $PERSO_VAR['nom'];
			}else{
			
				//Trouver le badge sélectionné
				$i=0; $trouve=false;
				while( $item = $perso->getInventaire($db, $i++) && !$trouve)
					if($item->getId() == $_POST['badge'])
						$trouve = true;
				
				if ($trouve)
					$badge_txt = 'Une personne vous présente son Visa Vert: ' . $item->getNom()
					. ' (' . $item->getDescription() . ') <hr />';
				else
					return fctErrorMSG('Le badge que vous avez sélectionné n\'existe pas.', '?popup=1&m=Action_Parler');
				
			}
			
			$_POST['msg'] = $badge_txt . $_POST['msg'];
		}
		
		
		if (!isset($_POST['to'])) { 
			$_POST['to']=0;
		}else{
			//Si des destinataires sont recu, les valider et leur faire parvenir le message
			for($i=0;$i<count($_POST['to']);$i++)
				if (!$perso->getLieu()->confirmPerso($db, $perso, $_POST['to'][$i]))
					array_remove($_POST['to'], $i);
		}
		
		//Copier le message dans les HE
		Member_He::add($db, $perso->getId(), $_POST['to'], 'parler', $_POST['msg']);
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>