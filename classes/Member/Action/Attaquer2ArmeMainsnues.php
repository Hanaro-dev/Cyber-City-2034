<?php
/** Gestion du tour d'attaque
*
* @package Member_Action
*/
class Member_Action_Attaquer2ArmeMainsnues extends Member_Action_Attaquer2{

	/** Lancer une attaque
	*
	* Exemple d'utilisation - Attaquer une victime à mains nues
	* <code>
	* Member_Action_Attaquer2ArmeMainsnues::attaquer($db, $perso, $victime, 'TC', $log1, $log2, $log3, true);
	* </code>
	* <br>
	* 
	* @param object &$db  Connexion à la base de donnée à utiliser, normalement: $db
	* @param object &$perso  Quel est le perso qui attaque
	* @param object &$victime  Qui est le perso attaqué
	* @param string $portee  Portee désirée par le joueur
	*
	* @return bool Retourne TRUE si la victime doit riposter à la fin du tour d'attaque
	*/
	public static function attaquer(&$db, &$perso, &$victime, $portee, &$msgPerso, &$msgVictime, &$msgLieu, $estUneRiposte=false)
	{
																									echo "<br />ID Vict.:" . $victime->getId();
		$ripostePossible				=	true; //Les attaques à mains nues sont toutes ripostable car la porté est TC
		$bonus1							=	1.00;
		$bonus2							=	1.00;
		$bonus3							=	1.00;
		$dommagesBonus					=	0;
		
		
		if(isset($_POST['furtif'])){
																									echo "RealLvl:" .$perso->getCompRealLevel($db, "FRTV");
			$tauxReussite = $perso->getChancesReussite($db, "FRTV");
			
			$de = rand(1,100);																		echo "<br /> Test furtivité [Dé={$de}, TauxRéussite={$tauxReussite}]";
			if($de < $tauxReussite){
				$msgPerso	.= "\nVous arrivez à vous approcher furtivement de votre victime.";
				$msgVictime	.= "\nUne personne s'approche furtivement de vous sans que vous ne vous vous en rendiez compte.";
				$msgLieu	.= "\nUne personne s'approche discrètement d'une autre.";
				$bonus1		=	1.30;	// + 30% de chances de réussir l'attaque
				$ripostePossible			=	false;
			}else{
				$msgPerso	.= "\nVous essayez de vous approcher furtivement de votre victime, mais vous vous faite repérer.";
				$msgVictime	.= "\nUne personne tente de s'approcher furtivement de vous avec une démarche suspecte.";
				$msgLieu	.= "\nUne personne s'approche discrètement d'une autre, mais cette dernière l'appercoit.";
				$bonus1		=	0.90;	// -10% de chances de réussir l'attaque
			}
		}
		
		//Vérifier si l'attaque est ciblée
		if ($_POST['type_att'] == 'cible'){
			$bonus2			=	0.75; //-25% de chances de réussir l'attaque
			$dommagesBonus	=	15; //15% de dommages en plus
		}
		
		
		$persoArme	= $perso->getArme($db);
		
		//Effectuer X attaques, selon la cadence de l'arme (tir par tour)
		//for($tir=1; $tir<=$persoArme->getTirParTour(); $tir++){
		//																		echo "<br /> Tir #{$tir}";
			//Tester la fiabilité de l'arme
			//$de = rand(1,100);													echo "<br /> Test fiabilité [Dé={$de}, ArmeFiabilite=" . $perso->getArme($db)->getFiabilite() . "]";
			//if ($de >= $persoArme->getFiabilite()){ //l'arme ÉCHOUE le test de fiabilité
			//	$msgPerso	.= "\nL'arme semble mal fonctionner, l'attaque échoue.";
			//	$msgVictime	.= "\nL'arme de votre aggresseur semble mal fonctionner, son attaque échoue.";
			//	break; //Fin de l'attaque
			//}
			
			/*
			if($persoArme->getMunition()==0) { //Il ne reste plus de munition
				$msgPerso	.= "\nVous êtes à court de munition.";
				$msgVictime	.= "\nVotre aggresseur est à court de munition.";
				break; //Fin de l'attaque
			}
			//Rabaisser les munitions de l'arme
			$persoArme->useMunition(); // Munition -=1
			*/
			
			//Calcul du taux de réussite
			$tauxReussiteCAC = $perso->getChancesReussite($db, "ARMC");
			$tauxReussiteESQ = $victime->getChancesReussite($db, "ESQV");
			$tauxReussite = ($tauxReussiteCAC + (100-$tauxReussiteESQ) ) /2 * $bonus1 * $bonus2 * $bonus3;
																										echo "<br />ARMC:" . $perso->getCompRealLevel($db, "ARMC") . " ESQV:" . $victime->getCompRealLevel($db, "ESQV");
			$de = rand(1,100);																			echo "<br /> Test réussite [Dé={$de}, TauxRéussite={$tauxReussite}]";
			if ($de >= $tauxReussite){	//L'attaque ÉCHOUE car la victime esquive
				if (!$estUneRiposte){
					$msgPerso	.= "\nVotre attaque échoue, votre victime esquive.";
					$msgVictime	.= "\nVous arrivez à esquiver l'attaque.";
					$msgLieu	.= "\nLa victime arrive à esquiver l'attaque.";
				}else{
					$msgPerso	.= "\nVotre victime tente une riposte mais vous esquivez.";
					$msgVictime	.= "\nVous tentez une riposte mais votre opposant esquive.";
					$msgLieu	.= "\nLa victime tente une attaque mais se fait esquiver.";
				}
			}else{	//L'attaque réussit, calculer les dommages à la victime
					
					
					//Trouver la localisation du coup porté
					if ($_POST['type_att'] == 'cible')
						$localisationDuCoup = $_POST['zones'];
					else
						$localisationDuCoup = Member_Action_Attaquer2::localisationDuCoup($tauxReussite);
					
					
					//Calculer les dégats infligés à la victime
					$degats = round(($perso->getCompRealLevel($db, "ARMC")+2)/2 * (1+$dommagesBonus/100));
					
					
					//Vérifier si la victime a une armure à l'endroit du coup
					$i=0;
					while( $item = $victime->getInventaire($db, $i++))
						if(is_a($item, 'Member_ItemDefense' . $localisationDuCoup['nom']))
							if($item->isEquip()){
								$victimeDefense = $item;
								break;
							}
						
					
					
					// La victime possède une armure
					if(isset($victimeDefense)){
						
						//Calculer les dégats absorbé par l'armure
						if ($degats > $victimeDefense->getResistanceSeuil()){ //Les dégats sont plus grand que le seuil de l'armure
							if ( ($victimeDefense->getResistance() - $victimeDefense->getResistanceSeuil()) <  0){ //L'armure est morte
								$degats -= $victimeDefense->getResistance(); // Calculer les dégats après l'impact sur l'armure
								$victimeDefense->setResistance($db, 0); //L'armure à perdu toute sa résistance
							}else{
								$degats -=  $victimeDefense->getResistanceSeuil(); //Calculer les dégats après l'impact sur l'armure
								$victimeDefense->changeResistance($db, '-', $victimeDefense->getResistanceSeuil()); //L'armure perd en résistance l'équivalent de ce qu'elle peut encaisser pour ce coup (le seuil)
							}
							
						}else{ //Les dégats sont inférieur au seuil de l'armure
							
							//si la résistance de l'armure tombe à O, ajoutez les dommages restants au perso
							if ( ($victimeDefense->getResistance() - $degats) < 0){ //L'armure est morte (La résistance totale est plus base que le seuil et les dégas détruisent l'armure)
								$degats -= $victimeDefense->getResistance(); //Calculer les dégats après l'impact sur l'armure
								$victimeDefense->setResistance($db, 0); //L'armure à perdu toute sa résistance
							}else{
								$degats = 0;
								$victimeDefense->changeResistance($db, '-', $degats); //Soustraire tout les degats aux PRs de l'armure
							}
						}
						
						
						
						//Vérifier si le perso se blesse en frappant sur l'armure
						if($perso->getStatRealLevel($db, 'FOR') < $victimeDefense->getResistance()){ //Le perso se blesse
							//Calculer la différence entre l'armure et la force de frape. On ne peux pas se faire plus mal que notre propre force.
							$dif = $victimeDefense->getResistance() - $perso->getStatRealLevel($db, 'FOR');
							if ($dif > $perso->getStatRealLevel($db, 'FOR'))
								$dif = $perso->getStatRealLevel($db, 'FOR');
							
							$dif = round($dif);
							$perso->changePv('-', $dif);
							$perso->setPv($db);
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
					
					//Mettre à jour les PV de la victime
					$degats *= (1+$localisationDuCoup['multiplicateur']/10); //Modifier les dégats non-absorbé en fonction de la zone touchée. 
																			// Dans le cas des attaques à mains nues, les dommages ne sont pas x3, mais x30%, par exemple.
					$degats = round($degats);
					$victime->changePv('-', $degats);
					$victime->setPv($db);
					if (!$estUneRiposte){
						$msgPerso	.= "\nVous arrivez à blesser votre victime de {$degats} PV.";
						$msgVictime	.= "\nVotre aggresseur arrive à vous blesser de {$degats} PV.";
						$msgLieu	.= "\nL'aggresseur arrive à porter un coup.";
					}else{
						$msgPerso	.= "\nVotre victime arrive à vous infliger {$degats} PV de dégat.";
						$msgVictime	.= "\nVous arrivez à blesser votre aggresseur de {$degats} PV.";
						$msgLieu	.= "\nLa victime arrive à porter un coup.";
					}
			}
			
			
		//} // Fin de la boucle des tirs/tour
		
		
		return $ripostePossible;
		
	}
}
?>
