<?php
/** Gestion du tour d'attaque
*
* @package Member_Action
*/
class Member_Action_Attaquer2ArmeFeu extends Member_Action_Attaquer2{

	/** Lancer une attaque
	*
	* Exemple d'utilisation - Attaquer une victime avec une arme � feu
	* <code>
	* Member_Action_Attaquer2ArmeFeu::attaquer($db, $perso, $victime, 'TC', $log1, $log2, $log3, true);
	* </code>
	* <br>
	* 
	* @param object &$db  Connexion � la base de donn�e � utiliser, normalement: $db
	* @param object &$perso  Quel est le perso qui attaque
	* @param object &$victime  Qui est le perso attaqu�
	* @param string $portee  Portee d�sir�e par le joueur
	*
	* @return bool Retourne TRUE si la victime doit riposter � la fin du tour d'attaque
	*/
	public static function attaquer(&$db, &$perso, &$victime, $portee, &$msgPerso, &$msgVictime, &$msgLieu, $estUneRiposte=false)
	{
																									echo "<br />ID Vict.:" . $victime->getId();
		$ripostePossible				=	true; //Les attaques � mains nues sont toutes ripostable car la port� est TC
		$bonus1							=	1.00;
		$bonus2							=	1.00;
		$bonus3							=	1.00;
		$dommagesBonus					=	0;
		
		//V�rifier si la furtivit� r�ussie
		var_dump($_POST['furtif']);
		var_dump($_POST['type_att']);
		
		if(isset($_POST['furtif'])){
																									echo "@:" .$perso->getCompRealLevel($db, "FRTV");
			$tauxReussite = $perso->getChancesReussite($db, "FRTV");
			
			$de = rand(1,100);																		echo "<br /> Test furtivit� [D�={$de}, TauxR�ussite={$tauxReussite}]";
			if($de < $tauxReussite){
				$msgPerso	.= "\nVous arrivez � vous approcher furtivement de votre victime.";
				$msgVictime	.= "\nUne personne s'approche furtivement de vous sans que vous ne vous vous en rendiez compte.";
				$msgLieu	.= "\nUne personne s'approche discr�tement d'une autre.";
				$bonus		=	1.30;	// + 30% de chances de r�ussir l'attaque
				$ripostePossible			=	false;
			}else{
				$msgPerso	.= "\nVous essayez de vous approcher furtivement de votre victime, mais vous vous faite rep�rer.";
				$msgVictime	.= "\nUne personne tente de s'approcher furtivement de vous avec une d�marche suspecte.";
				$msgLieu	.= "\nUne personne s'approche discr�tement d'une autre, mais cette derni�re l'appercoit.";
				$bonus		=	0.90;	// -10% de chances de r�ussir l'attaque
			}
		}
		
		//V�rifier si l'attaque est cibl�e
		if ($_POST['type_att'] == 'cible'){
			$bonus2			=	0.75; //-25% de chances de r�ussir l'attaque
			$dommagesBonus	=	15; //15% de dommages en plus
		}
		
		
		$persoArme	= $perso->getArme($db);
		
		self::tblDistance($portee);
		
		
		
		//Effectuer X attaques, selon la cadence de l'arme (tir par tour)
		for($tir=1; $tir<=$persoArme->getTirParTour(); $tir++){
																				echo "<br /> Tir #{$tir}";
			//Tester la fiabilit� de l'arme
			$de = rand(1,100);													echo "<br /> Test fiabilit� [D�={$de}, ArmeFiabilite=" . $perso->getArme($db)->getFiabilite() . "]";
			if ($de >= $persoArme->getFiabilite()){ //l'arme �CHOUE le test de fiabilit�
				$msgPerso	.= "\nL'arme semble mal fonctionner, l'attaque �choue.";
				$msgVictime	.= "\nL'arme de votre aggresseur semble mal fonctionner, son attaque �choue.";
				break; //Fin de l'attaque
			}
			
			
			if($persoArme->getMunition()==0) { //Il ne reste plus de munition
				$msgPerso	.= "\nVous �tes � court de munition.";
				$msgVictime	.= "\nVotre aggresseur est � court de munition.";
				break; //Fin de l'attaque
			}
			//Rabaisser les munitions de l'arme
			$persoArme->useMunition(1); // Munition -=1
			
			
			//Calcul du taux de r�ussite
			$tauxReussiteARM = $perso->getChancesReussite($db, "ARMF");
			$tauxReussiteESQ = $victime->getChancesReussite($db, "ESQV");
			$tauxReussite = ($tauxReussiteARM + (100-$tauxReussiteESQ) ) /2 * $bonus1 * $bonus2 * $bonus3;
																										echo "<br />ARMC:" . $perso->getCompRealLevel($db, "ARMC") . " ESQV:" . $victime->getCompRealLevel($db, "ESQV");
			$de = rand(1,100);																			echo "<br /> Test r�ussite [D�={$de}, TauxR�ussite={$tauxReussite}]";
			if ($de >= $tauxReussite){	//L'attaque �CHOUE car la victime esquive
				if (!$estUneRiposte){
					$msgPerso	.= "\nVotre attaque �choue, votre victime esquive.";
					$msgVictime	.= "\nVous arrivez � esquiver l'attaque.";
					$msgLieu	.= "\nLa victime arrive � esquiver l'attaque.";
				}else{
					$msgPerso	.= "\nVotre victime tente une riposte mais vous esquivez.";
					$msgVictime	.= "\nVous tentez une riposte mais votre opposant esquive.";
					$msgLieu	.= "\nLa victime tente une attaque mais se fait esquiver.";
				}
			}else{	//L'attaque r�ussit, calculer les dommages � la victime
					
					
					//Trouver la localisation du coup port�
					if ($_POST['type_att'] == 'cible')
						$localisationDuCoup = $_POST['zones'];
					else
						$localisationDuCoup = Member_Action_Attaquer2::localisationDuCoup($tauxReussite);
					
					
					//Calculer les d�gats inflig�s � la victime
					$degats = round(($perso->getCompRealLevel($db, "ARMC")+2)/2 * (1+$dommagesBonus/100));
					
					
					//V�rifier si la victime a une armure � l'endroit du coup
					$i=0;
					while( $item = $victime->getInventaire($db, $i++))
						if(is_a($item, 'Member_ItemDefense' . $localisationDuCoup['nom']))
							if($item->isEquip()){
								$victimeDefense = $item;
								break;
							}
						
					
					
					// La victime poss�de une armure
					if(isset($victimeDefense)){
						
						//Calculer les d�gats absorb� par l'armure
						if ($degats > $victimeDefense->getResistanceSeuil()){ //Les d�gats sont plus grand que le seuil de l'armure
							if ( ($victimeDefense->getResistance() - $victimeDefense->getResistanceSeuil()) <  0){ //L'armure est morte
								$degats -= $victimeDefense->getResistance(); // Calculer les d�gats apr�s l'impact sur l'armure
								$victimeDefense->setResistance($db, 0); //L'armure � perdu toute sa r�sistance
							}else{
								$degats -=  $victimeDefense->getResistanceSeuil(); //Calculer les d�gats apr�s l'impact sur l'armure
								$victimeDefense->changeResistance($db, '-', $victimeDefense->getResistanceSeuil()); //L'armure perd en r�sistance l'�quivalent de ce qu'elle peut encaisser pour ce coup (le seuil)
							}
							
						}else{ //Les d�gats sont inf�rieur au seuil de l'armure
							
							//si la r�sistance de l'armure tombe � O, ajoutez les dommages restants au perso
							if ( ($victimeDefense->getResistance() - $degats) < 0){ //L'armure est morte (La r�sistance totale est plus base que le seuil et les d�gas d�truisent l'armure)
								$degats -= $victimeDefense->getResistance(); //Calculer les d�gats apr�s l'impact sur l'armure
								$victimeDefense->setResistance($db, 0); //L'armure � perdu toute sa r�sistance
							}else{
								$degats = 0;
								$victimeDefense->changeResistance($db, '-', $degats); //Soustraire tout les degats aux PRs de l'armure
							}
						}
						
						
						
						//V�rifier si le perso se blesse en frappant sur l'armure
						if($perso->getStatRealLevel($db, 'FOR') < $victimeDefense->getResistance()){ //Le perso se blesse
							//Calculer la diff�rence entre l'armure et la force de frape. On ne peux pas se faire plus mal que notre propre force.
							$dif = $victimeDefense->getResistance() - $perso->getStatRealLevel($db, 'FOR');
							if ($dif > $perso->getStatRealLevel($db, 'FOR'))
								$dif = $perso->getStatRealLevel($db, 'FOR');
							
							$dif = round($dif);	
							$perso->changePv($db, '-', $dif);
							if (!$estUneRiposte){
								$msgPerso	.= "\nVous frappez directement sur une armure et vous vous blessez de {$dif} PV.";
								$msgVictime	.= "\nVotre aggresseur frappe sur votre armure et se fait mal.";
								$msgLieu	.= "\nL'aggresseur frappe sur l'armure de son opposant et se fait mal.";
							}else{
								$msgPerso	.= "\nLa victime frappe directement sur l'armure et se blesse.";
								$msgVictime	.= "\nVous frappez directement sur une armure et vous vous blessez de {$dif} PV.";
								$msgLieu	.= "\nLa victime frappe sur l'armure de son opposant et se fait mal.";
							}
						}
						
					}
					
					//Mettre � jour les PV de la victime
					$degats *= (1+$localisationDuCoup['multiplicateur']/10); //Modifier les d�gats non-absorb� en fonction de la zone touch�e. 
																			// Dans le cas des attaques � mains nues, les dommages ne sont pas x3, mais x30%, par exemple.
					$degats = round($degats);					
					$victime->changePv('-', $degats);
					$victime->setPv($db);
					if (!$estUneRiposte){
						$msgPerso	.= "\nVous arrivez � blesser votre victime de {$degats} PV.";
						$msgVictime	.= "\nVotre aggresseur arrive � vous blesser de {$degats} PV.";
						$msgLieu	.= "\nL'aggresseur arrive � porter un coup.";
					}else{
						$msgPerso	.= "\nVotre victime arrive � vous infliger {$degats} PV de d�gat.";
						$msgVictime	.= "\nVous arrivez � blesser votre aggresseur de {$degats} PV.";
						$msgLieu	.= "\nLa victime arrive � porter un coup.";
					}
			}
			
			
		} // Fin de la boucle des tirs/tour
		
		
		return $ripostePossible;
		
	}
	
	
	private static function tblDistance($portee){
		switch ($portee){
			case 'TC':	return 0.80;	break;
			case 'C':	return 1.15;	break;
			case 'M':	return 1.00;	break;
			case 'L':	return 0.85;	break;
			case 'TL':	return 0.70;	break;
		}
	}
}
?>