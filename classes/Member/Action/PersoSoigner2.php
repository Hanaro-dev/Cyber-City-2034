<?php
/** Soigner un personnage (blessures superficielles): But soigner le perso
*
* @package Member_Action
*/

//Quelques trucs � v�rifier, au niveau des ID, cheats etc

class Member_Action_PersoSoigner2 {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$pvSuplementaires=0.75;//1pa soigne 0.75pv
		$facteurResistance = 3; //1r�sitance soigne 3 pv


		if(!isset($_POST['itemId']))
			return fctErrorMSG('Aucune trousse n\'a �t� s�lectionn�e', '?popup=1&m=Action_PersoSoigner');
		if(!isset($_POST['blesse']))
			return fctErrorMSG('Aucun personnage � soigner s�lectionn�', '?popup=1&m=Action_PersoSoigner');
	
		
			
		$itemId = $_POST['itemId'];			
		//R�cup du perso � soigner
		$i=0;
		while($arrPerso = $perso->getLieu()->getPerso($db, $perso, $i++)){
			if(($arrPerso->getId() == $_POST['blesse']))
			{
				if($arrPerso->isAutonome() == true)
				{
					$blesse = $arrPerso;
				}	
			}
		}
		if($blesse == null)
			return fctErrorMSG('Cheat: Perso non soignable ou n\'�tant pas dans votre lieu', '?popup=1&m=Action_PersoSoigner');	
		if(($blesse->getSoin() > 0) && ($itemId == 0))
			return fctErrorMSG('Cette personne a d�j� re�u les soins de base.', '?popup=1&m=Action_PersoSoigner');	
				
		if($itemId != 0)
		{			
		//r�cup de la trousse
			$i=0;
			$trousse=array();
			while( $item = $perso->getInventaire($db, $i++)){
				if(is_a($item, 'Member_ItemTrousse')){
					if($item->getInvId() == $_POST['itemId'])
					{
						if($item->getResistance() != 0)
							$trousse = $item;
					}
				}	
			}		
			if($trousse == null)
				return fctErrorMSG('Cheat: Trousse non conforme ou ne vous appartenant pas.', '?popup=1&m=Action_PersoSoigner');	
				
		$pa = $_POST[$itemId];//Nombre de PAs saisis par l'utilisateur
		if(!is_numeric($pa))
			return fctErrorMSG('Nombre de PA non num�rique.', '?popup=1&m=Action_PersoSoigner');
		}
		else
		{
			$sql = 'SELECT *
					FROM '.DB_PREFIX.'item_inv
					LEFT JOIN '.DB_PREFIX.'item_db ON (inv_dbid=db_id)
					WHERE inv_id=0';
			$query = $db->query($sql,__FILE__,__LINE__);
			$result= mysql_fetch_assoc($query);
				
			$pa = 5;
			$trousse = new Member_ItemTrousse($result);
		}
				


	
	
	if($pa > $perso->getPa())
			return fctErrorMSG('Vous n\'avez pas assez de PA pour effectuer cette action.', '?popup=1&m=Action_PersoSoigner');	

	$reussiteScrs =  $perso->getChancesReussite($db,"MEDS");
	$compteurPV = 0;
	$pafinal = 0;
	
	if($itemId != 0)
	{
		//TQ le nombre de PA � d�penser n'est pas d�pens�, que le perso n'est pas soign� et qu'il reste de la r�sistance � la trousse
		for($i=0;($i<$pa) && ($blesse->getPv() + round($compteurPV) < $blesse->getPvMax()) && (round( round($compteurPV) / $facteurResistance) < $trousse->getResistance()) ;$i++) 
		{
			$de = rand(1,100);
			if(($de < $reussiteScrs))
			{
				$compteurPV = $compteurPV + $pvSuplementaires;

			}
			$pafinal = $pafinal + 1;
		}
	}
	else
	{
		//TQ le nombre de PA � d�penser n'est pas d�pens�, que le perso n'est pas soign� 
		for($i=0;($i<$pa) && ($blesse->getPv() + round($compteurPV) < $blesse->getPvMax());$i++) 
		{
			$de = rand(1,100);
			if(($de < $reussiteScrs))
			{
				$compteurPV = $compteurPV + $pvSuplementaires;

			}
			$pafinal = $pafinal + 1;
		}	

	}
	
	$compteurPV = round($compteurPV);
	
	if($itemId != 0)
	{
		$resistanceRetiree = round( $compteurPV / $facteurResistance);
		$resitanceMaj = $trousse->getResistance()- $resistanceRetiree;
		
		$sql = 'UPDATE '.DB_PREFIX.'item_inv
				SET inv_resistance='.$resitanceMaj.'
				WHERE inv_id='.$trousse->getInvId().'';
		$db->query($sql,__FILE__,__LINE__);
	}
	else
	{
		$sql = 'UPDATE '.DB_PREFIX.'perso
				SET soin=1
				WHERE id='.$blesse->getId().'';
		$db->query($sql,__FILE__,__LINE__);
		$resistanceRetiree = "'Soins de base'";
	}
	
	echo "Le taux de r�ussite �tait de: ".$reussiteScrs."% , ".$pafinal." PA on �t� consomm�s, ".$compteurPV." PV ont �t� remis, ".$resistanceRetiree." points de r�sistance ont �t�s cosomm�s.";
	
	$perso->changePa("-",$pafinal);
	$perso->setPa($db,$perso->getPa());
	$blesse->changePv("+",$compteurPV);
	$blesse->setPv($db,$blesse->getPv());
	

	
	//Retourner le template compl�t�/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/persoSoigner2.htm',__FILE__,__LINE__);	
	}
}

?>