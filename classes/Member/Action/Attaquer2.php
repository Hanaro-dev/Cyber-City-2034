<?php
/** Gestion du tour d'attaque
*
* @package Member_Action
*/
class Member_Action_Attaquer2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
		
		
		
		// ### VALIDATIONS
		
		//V�rifier l'�tat du perso
		if(!$perso->isConscient())
			return fctErrorMSG('Votre n\'�tes pas en �tat d\'effectuer cette action.', '?popup=1&m=Action_Attaquer');
		
		
		
		//V�rifier si un personnage � �t� s�lectionn�
		if(!isset($_POST['perso_id']))
			return fctErrorMSG('Vous devez s�lectionner la personne que vous d�sirez attaquer.', '?popup=1&m=Action_Attaquer');
		
		
		
		//V�rifier si un nombre de tours � �t� s�lectionn�
		if (!isset($_POST['tours']) || !is_numeric($_POST['tours']))
			return fctErrorMSG('Vous devez s�lectionner le nombre de tour(s) � effectuer.', '?popup=1&m=Action_Attaquer');
		
		
		
		//V�rifier si nous avons assez de PA pour attaquer
		$cout_pa = Member_Action_Attaquer::coutPaTotal($db, $perso, $_POST['tours']);
		if ($_POST['type_att'] == 'cible')	$cout_pa+=15;
		if (isset($_POST['furtif']))		$cout_pa+=15;
		if($perso->getPa() < $cout_pa)
			return fctErrorMSG('Vous n\'avez pas assez de PA pour effectuer cette action.', '?popup=1&m=Action_Attaquer');
		
		
		
		//V�rifier si le perso s�lectionn� est bien pr�sent dans le lieu actuel
		$i=0;
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() == $_POST['perso_id']){
				$victime = $tmp;
				break;
			}
		if (!isset($victime))
			return fctErrorMSG('Le personnage s�lectionn� n\'est pas pr�sent dans le lieu actuel.', '?popup=1&m=Action_Attaquer');
		
		
		
		//ATTAQUE CIBL�E: V�rifier si une zone � �t� s�lectionn�e
		if ($_POST['type_att'] == 'cible' && !isset($_POST['zones']))
			return fctErrorMSG('Une attaque cibl�e doit avoir une zone � cibler.', '?popup=1&m=Action_Attaquer');
			
			
			
		//CHEAT+HACK: V�rifier l'�tat innitial de la  victime
		if(!$perso->isVivant())
			return fctErrorMSG('Vous ne pouvez pas attaquer une personne d�j� morte.', '?popup=1&m=Action_Attaquer');
		
		
		
		//CHEAT+HACK: ATTAQUE CIBL�E: Valider qu'une attaque cibl� n'a qu'un seul tour
		if ($_POST['type_att'] == 'cible' && $_POST['tours']>1)
			return fctErrorMSG('Une attaque cibl�e ne peut avoir qu\'un seul tour.', '?popup=1&m=Action_Attaquer');
		
		
		//CHEAT+HACK: V�rifier si la port�e est valide
		$porteeSelectionnee = $_POST['portee'];
		$porteeArme = $perso->getArme($db)->getPortee();
		$dimensionLieu = $perso->getLieu()->getDimension();
		
		$porteeAutoriseeMax = Member_Action_Attaquer::porteePlusPetite($dimensionLieu,$PorteeArme) ? $dimensionLieu : $porteeArme;
		
		if(Member_Action_Attaquer::porteePlusPetite($porteeAutoriseeMax, $porteeSelectionnee))
			return fctErrorMSG('La port�e ne peut d�passer la capacit� de l\'arme ou la taille du lieu.', '?popup=1&m=Action_Attaquer');
		
		
		
		// ### D�BUT DU MOTEUR D'ATTAQUE
		$tour = 0;
		
		//Messages d'intro
		$msgPerso = 'Vous tentez une attaque';
		$msgVictime = 'Une personne tente une attaque';
		$msgLieu = 'Vous voyez une personne tenter une attaque';
		
		if (isset($_POST['furtif'])){
			$msgPerso .= ' furtive';
			$att_furtive = true;
		}
		
		if ($_POST['type_att'] == 'cible')
			$msgPerso .= " cibl�e (Zone vis�e: " . $_POST['zones'] . "):\n\n";
		else
			$msgPerso .= " par tours (tour(s) tent�s: " . $_POST['tours'] . "):\n\n";
		
		
		
		$attStop = false;
		do{ //Boucle des tours d'attaques
			$tour++;													echo "<hr />Tour #{$tour}<br />";
			
			$msgPerso .= "\nTour #" . $tour;
			
			
			//Lancer le tour d'attaque en fonction du type d'arme utilis�
			$msg = "Une attaque est tent�e:\n";
			switch(get_class($perso->getArme($db))){
				case 'Member_ItemArmeMainsnues':
					$riposte = Member_Action_Attaquer2ArmeMainsnues::attaquer($db, $perso, $victime, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, false);
					break;
				case 'Member_ItemArmeBlanche':
					$riposte = Member_Action_Attaquer2ArmeBlanche::attaquer($db, $perso, $victime, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, false);
					break;
				case 'Member_ItemArmeFeu':
					$riposte = Member_Action_Attaquer2ArmeFeu::attaquer($db, $perso, $victime, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, false);
					break;
				//case 'Member_ItemArmeExplosive':
				//	$riposte = Member_Action_Attaquer2ArmeExplosive::attaquer($db, $perso, $victime, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, false);
				//	break;
				//case 'Member_ItemArmeLourde':
				//	$riposte = Member_Action_Attaquer2ArmeLourde::attaquer($db, $perso, $victime, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, false);
				//	break;
				//case 'Member_ItemArmeParalysante':
				//	$riposte = Member_Action_Attaquer2ArmeParalysante::attaquer($db, $perso, $victime, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, false) ;
				//	break;
				default:
					fctBugReport($db, 'Type d\'arme innexistant (' . get_class($perso->getArme($db)) . ')', array($perso, $perso->getArme($db)), __FILE__, __LINE__);
					break;
			}
			
			if(isset($_POST['furtif']))
				unset($_POST['furtif']); //De cette facon, la furtivit� est uniquement tent� au premier tour.
				
			//Au besoin, lancer la riposte de la part de la victime
			if ($riposte && $victime->isAutonome()){
				if (DEBUG_MODE)
					echo "<br /><br />Riposte:";
				$msg = "Une riposte suit imm�diatement l'attaque:\n";
				switch(get_class($victime->getArme($db))){
					case 'Member_ItemArmeMainsnues':
						$riposte = Member_Action_Attaquer2ArmeMainsnues::attaquer($db, $victime, $perso, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, true);
						break;
					case 'Member_ItemArmeBlanche':
						$riposte = Member_Action_Attaquer2ArmeBlanche::attaquer($db, $victime, $perso, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, true);
						break;
					case 'Member_ItemArmeFeu':
						$riposte = Member_Action_Attaquer2ArmeFeu::attaquer($db, $victime, $perso, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, true);
						break;
					//case 'Member_ItemArmeExplosive':
					//	$riposte = Member_Action_Attaquer2ArmeExplosive::attaquer($db, $victime, $perso, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, true);
					//	break;
					//case 'Member_ItemArmeLourde':
					//	$riposte = Member_Action_Attaquer2ArmeLourde::attaquer($db, $victime, $perso, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, true);
					//	break;
					//case 'Member_ItemArmeParalysante':
					//	$riposte = Member_Action_Attaquer2ArmeParalysante::attaquer($db, $victime, $perso, $porteeSelectionnee, $msgPerso, $msgVictime, $msgLieu, true);
					//	break;
					default:
						fctBugReport($db, 'Type d\'arme innexistant (' . get_class($victime->getArme($db)) . ')', array($victime, $victime->getArme($db)), __FILE__, __LINE__);
						break;
				}
			}
			
			
				
			//V�rifier les options d'arr�t
			if ($_POST['att_stop']=='normal'	&& !$victime->isNormal())		$attStop = true;
			if ($_POST['att_stop']=='autonome'	&& !$victime->isAutonome())		$attStop = true;
			if ($_POST['att_stop']=='conscient'	&& !$victime->isConscient())	$attStop = true;
			
			//Si il faut arr�ter, v�rifier si l'arr�t r�ussit ou �choue
			if($attStop){
				$reussite_arret = 85 + $perso->getStatRealLevel($db, 'PER')*3;
				
				$de = rand(1,100);
				if ($de >= $reussite_arret) //�CHEC de l'arr�t
					$attStop = false;
			}
			
			
			if ($attStop){
				$msgPerso	.= "\n\nVous d�cidez de vous en tenir l�, son compte est bon.";
				$msgVictime	.= "\n\nL'aggresseur arr�te soudainement son attaque: votre compte est bon.";
				$msgLieu	.= "\n\nL'aggresseur arr�te soudainement son attaque.";
			}
			
			
			//V�rifier si le nombre de tours maximum est atteind (ou d�pass�)
			if ($tour>=$_POST['tours'])
				$attStop = true;
				
			
		}while(!$attStop); //Fin de la boucle des tours
		
		//### FIN DU MOTEUR D'ATTAQUE
		
		
		
		
		
		
		
		
		//Retirer les PA de l'attaquant
		$perso->changePa('-', $cout_pa);
		$perso->setPa($db);
		
		//Gain en STAT+COMP
		switch(get_class($perso->getArme($db))){
			case 'Member_ItemArmeMainsnues':
				if ($_POST['type_att'] == 'cible'){
					if (isset($att_furtive)){
						$msgPerso .= "\n" . $perso->setStat($db, array(	'AGI' => '+01',
																	'FOR' => '+03',
																	'DEX' => '-01',
																	'PER' => '-02',
																	'INT' => '-01' 	));
						$msgPerso .= "\n" . $perso->setComp($db, array(	'ARMC' => rand(1,3),
																	'FRTV' => rand(1,3)));
					}else{
						$msgPerso .= "\n" . $perso->setStat($db, array(	'AGI' => '+01',
																	'FOR' => '+02',
																	'DEX' => '-01',
																	'PER' => '-01',
																	'INT' => '-01'	));
						$msgPerso .= "\n" . $perso->setComp($db, array(	'ARMC' => rand(1,3))	);
					}
				}else{
					if (isset($att_furtive)){
						$msgPerso .= "\n" . $perso->setStat($db, array(	'AGI' => '+02',
																	'FOR' => '+01',
																	'DEX' => '-02',
																	'PER' => '+00',
																	'INT' => '-01'	));
						$msgPerso .= "\n" . $perso->setComp($db, array(	'ARMC' => ($tour*rand(1,3)), 
																	'FRTV' => rand(1,3))	);
					}else{
						$msgPerso .= "\n" . $perso->setStat($db, array(	'AGI' => '+01',
																	'FOR' => '+00',
																	'DEX' => '-02',
																	'PER' => '+01',
																	'INT' => '+00'	));
						$msgPerso .= "\n" . $perso->setComp($db, array(	'ARMC' => ($tour*rand(1,3))	));
					}
				}
				break;
			case 'Member_ItemArmeBlanche':
				
				break;
			case 'Member_ItemArmeFeu':
				
				break;
			//case 'Member_ItemArmeExplosive':
			//	
			//	break;
			//case 'Member_ItemArmeLourde':
			//	
			//	break;
			//case 'Member_ItemArmeParalysante':
			//	
			//	break;
		}
		
		
		
		
		
		//Envoyer le message aux 2 personnes impliqu�s
		Member_He::add($db, $perso->getId(), $victime->getId(), 'attaque', $msgPerso, HE_TOUS, HE_AUCUN);
		Member_He::add($db, $perso->getId(), $victime->getId(), 'attaque', $msgVictime, HE_AUCUN, HE_TOUS);
		
		//Envoyer le message � tout les gens pr�sent sur le lieu
		$i=0;
		$arrPersoLieu = array();
		while( $tmp = $perso->getLieu()->getPerso($db, $perso, $i++))
			if($tmp->getId() != $perso->getId() && $tmp->getId() != $victime->getId())
				$arrPersoLieu[count($arrPersoLieu)] = $tmp->getId();
		Member_He::add($db, array($perso->getId(), $victime->getId()), $arrPersoLieu, 'attaque', $msgLieu, HE_AUCUN, HE_TOUS);
			
			
		
		//Rafraichir le HE
		if(!DEBUG_MODE)
			return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
		
	}
	
	
	
	
	
	
	
	protected static function localisationDuCoup($tauxReussite)
	{
		//Tableau qui d�termine la localisation du coup
		$localisation = array(
			0=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 15 %
				1=>array('min'=>16, 'nom'=>'Torse', 'multiplicateur'=>2), // 38 %
				2=>array('min'=>54, 'nom'=>'Bras' , 'multiplicateur'=>1), // 15 %
				3=>array('min'=>69, 'nom'=>'Main' , 'multiplicateur'=>1), // 07 %
				4=>array('min'=>76, 'nom'=>'Jambe', 'multiplicateur'=>1), // 15 %
				5=>array('min'=>91, 'nom'=>'Pied' , 'multiplicateur'=>1), // 05 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			1=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 19 %
				1=>array('min'=>20, 'nom'=>'Torse', 'multiplicateur'=>2), // 38 %
				2=>array('min'=>58, 'nom'=>'Bras' , 'multiplicateur'=>1), // 13 %
				3=>array('min'=>71, 'nom'=>'Main' , 'multiplicateur'=>1), // 07 %
				4=>array('min'=>78, 'nom'=>'Jambe', 'multiplicateur'=>1), // 14 %
				5=>array('min'=>92, 'nom'=>'Pied' , 'multiplicateur'=>1), // 04 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			2=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 23 %
				1=>array('min'=>24, 'nom'=>'Torse', 'multiplicateur'=>2), // 38 %
				2=>array('min'=>62, 'nom'=>'Bras' , 'multiplicateur'=>1), // 11 %
				3=>array('min'=>73, 'nom'=>'Main' , 'multiplicateur'=>1), // 07 %
				4=>array('min'=>80, 'nom'=>'Jambe', 'multiplicateur'=>1), // 12 %
				5=>array('min'=>92, 'nom'=>'Pied' , 'multiplicateur'=>1), // 04 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			3=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 28 %
				1=>array('min'=>29, 'nom'=>'Torse', 'multiplicateur'=>2), // 38 %
				2=>array('min'=>67, 'nom'=>'Bras' , 'multiplicateur'=>1), // 09 %
				3=>array('min'=>76, 'nom'=>'Main' , 'multiplicateur'=>1), // 06 %
				4=>array('min'=>82, 'nom'=>'Jambe', 'multiplicateur'=>1), // 11 %
				5=>array('min'=>93, 'nom'=>'Pied' , 'multiplicateur'=>1), // 03 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			4=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 31 %
				1=>array('min'=>32, 'nom'=>'Torse', 'multiplicateur'=>2), // 38 %
				2=>array('min'=>70, 'nom'=>'Bras' , 'multiplicateur'=>1), // 07 %
				3=>array('min'=>77, 'nom'=>'Main' , 'multiplicateur'=>1), // 06 %
				4=>array('min'=>83, 'nom'=>'Jambe', 'multiplicateur'=>1), // 10 %
				5=>array('min'=>93, 'nom'=>'Pied' , 'multiplicateur'=>1), // 03 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			5=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 35 %
				1=>array('min'=>36, 'nom'=>'Torse', 'multiplicateur'=>2), // 37 %
				2=>array('min'=>73, 'nom'=>'Bras' , 'multiplicateur'=>1), // 06 %
				3=>array('min'=>79, 'nom'=>'Main' , 'multiplicateur'=>1), // 05 %
				4=>array('min'=>84, 'nom'=>'Jambe', 'multiplicateur'=>1), // 09 %
				5=>array('min'=>93, 'nom'=>'Pied' , 'multiplicateur'=>1), // 03 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			6=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 39 %
				1=>array('min'=>40, 'nom'=>'Torse', 'multiplicateur'=>2), // 37 %
				2=>array('min'=>77, 'nom'=>'Bras' , 'multiplicateur'=>1), // 05 %
				3=>array('min'=>82, 'nom'=>'Main' , 'multiplicateur'=>1), // 05 %
				4=>array('min'=>87, 'nom'=>'Jambe', 'multiplicateur'=>1), // 07 %
				5=>array('min'=>94, 'nom'=>'Pied' , 'multiplicateur'=>1), // 02 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			7=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 41 %
				1=>array('min'=>42, 'nom'=>'Torse', 'multiplicateur'=>2), // 37 %
				2=>array('min'=>79, 'nom'=>'Bras' , 'multiplicateur'=>1), // 05 %
				3=>array('min'=>84, 'nom'=>'Main' , 'multiplicateur'=>1), // 04 %
				4=>array('min'=>88, 'nom'=>'Jambe', 'multiplicateur'=>1), // 06 %
				5=>array('min'=>94, 'nom'=>'Pied' , 'multiplicateur'=>1), // 02 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			8=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 43 %
				1=>array('min'=>44, 'nom'=>'Torse', 'multiplicateur'=>2), // 37 %
				2=>array('min'=>81, 'nom'=>'Bras' , 'multiplicateur'=>1), // 04 %
				3=>array('min'=>85, 'nom'=>'Main' , 'multiplicateur'=>1), // 04 %
				4=>array('min'=>89, 'nom'=>'Jambe', 'multiplicateur'=>1), // 05 %
				5=>array('min'=>94, 'nom'=>'Pied' , 'multiplicateur'=>1), // 02 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			9=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 46 %
				1=>array('min'=>47, 'nom'=>'Torse', 'multiplicateur'=>2), // 37 %
				2=>array('min'=>84, 'nom'=>'Bras' , 'multiplicateur'=>1), // 03 %
				3=>array('min'=>87, 'nom'=>'Main' , 'multiplicateur'=>1), // 04 %
				4=>array('min'=>91, 'nom'=>'Jambe', 'multiplicateur'=>1), // 05 %
				5=>array('min'=>96, 'nom'=>'Pied' , 'multiplicateur'=>1), // 02 %
				6=>array('min'=>98, 'nom'=>'Rien' , 'multiplicateur'=>0) // 03 %
			),
			10=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 50 %
				1=>array('min'=>51, 'nom'=>'Torse', 'multiplicateur'=>2), // 37 %
				2=>array('min'=>88, 'nom'=>'Bras' , 'multiplicateur'=>1), // 02 %
				3=>array('min'=>90, 'nom'=>'Main' , 'multiplicateur'=>1), // 02 %
				4=>array('min'=>92, 'nom'=>'Jambe', 'multiplicateur'=>1), // 02 %
				5=>array('min'=>94, 'nom'=>'Pied' , 'multiplicateur'=>1), // 02 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			11=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 55 %
				1=>array('min'=>56, 'nom'=>'Torse', 'multiplicateur'=>2), // 36 %
				2=>array('min'=>92, 'nom'=>'Bras' , 'multiplicateur'=>1), // 01 %
				3=>array('min'=>93, 'nom'=>'Main' , 'multiplicateur'=>1), // 01 %
				4=>array('min'=>94, 'nom'=>'Jambe', 'multiplicateur'=>1), // 01 %
				5=>array('min'=>95, 'nom'=>'Pied' , 'multiplicateur'=>1), // 01 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			12=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 64 %
				1=>array('min'=>65, 'nom'=>'Torse', 'multiplicateur'=>2), // 27 %
				2=>array('min'=>92, 'nom'=>'Bras' , 'multiplicateur'=>1), // 01 %
				3=>array('min'=>93, 'nom'=>'Main' , 'multiplicateur'=>1), // 01 %
				4=>array('min'=>94, 'nom'=>'Jambe', 'multiplicateur'=>1), // 01 %
				5=>array('min'=>95, 'nom'=>'Pied' , 'multiplicateur'=>1), // 01 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			13=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 73 %
				1=>array('min'=>74, 'nom'=>'Torse', 'multiplicateur'=>2), // 18 %
				2=>array('min'=>92, 'nom'=>'Bras' , 'multiplicateur'=>1), // 01 %
				3=>array('min'=>93, 'nom'=>'Main' , 'multiplicateur'=>1), // 01 %
				4=>array('min'=>94, 'nom'=>'Jambe', 'multiplicateur'=>1), // 01 %
				5=>array('min'=>95, 'nom'=>'Pied' , 'multiplicateur'=>1), // 01 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			14=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 82 %
				1=>array('min'=>83, 'nom'=>'Torse', 'multiplicateur'=>2), // 09 %
				2=>array('min'=>92, 'nom'=>'Bras' , 'multiplicateur'=>1), // 01 %
				3=>array('min'=>93, 'nom'=>'Main' , 'multiplicateur'=>1), // 01 %
				4=>array('min'=>94, 'nom'=>'Jambe', 'multiplicateur'=>1), // 01 %
				5=>array('min'=>95, 'nom'=>'Pied' , 'multiplicateur'=>1), // 01 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			),
			15=>array(
				0=>array('min'=>1 , 'nom'=>'Tete' , 'multiplicateur'=>3), // 90 %
				1=>array('min'=>91, 'nom'=>'Torse', 'multiplicateur'=>2), // 01 %
				2=>array('min'=>92, 'nom'=>'Bras' , 'multiplicateur'=>1), // 01 %
				3=>array('min'=>93, 'nom'=>'Main' , 'multiplicateur'=>1), // 01 %
				4=>array('min'=>94, 'nom'=>'Jambe', 'multiplicateur'=>1), // 01 %
				5=>array('min'=>95, 'nom'=>'Pied' , 'multiplicateur'=>1), // 01 %
				6=>array('min'=>96, 'nom'=>'Rien' , 'multiplicateur'=>0) // 05 %
			)
		);
		
		//Convertir la valeur du taux de r�ussite sur une �chelle de 0 � 20 (Le taux de r�ussite va de 0 � 200)
		$tauxReussiteSur20 = round(($tauxReussite/10),0);
		if ($tauxReussiteSur20 > 15)
			$tauxReussiteSur20 = 15; //Dans le cas d'�chec critique, il y a un maximum de "critique".
		
		//trouver ou le coup est localis�
		$de = rand(1,100);
		for($i=0; $i<=6; $i++)	//TECHNIQUE: D�tecter dans quel tranche du tableau se situe le r�sultat du D�
			if ($localisation[$tauxReussiteSur20][$i]['min'] > $de)
				return $localisation[$tauxReussiteSur20][$i-1];
		return $localisation[$tauxReussiteSur20][6];
	}
	
}
?>
