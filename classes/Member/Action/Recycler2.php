<?php
/** Recyclage des Items, but: vendre l'item et donner de l'argent au proprio
*
* @package Member_Action
*/
class Member_Action_Recycler2 {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
	$pa=5;
	$message="";
	
		//Vérifier que le lieu dans lequel est le perso permet bien de recycler les items
		$sql = "SELECT *
				FROM cc_lieu_menu
				WHERE lieutech='".$perso->getLieu()->getNomTech()."' && url='Recycler'";
		$result = $db->query($sql,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);	
		if(mysql_num_rows($result) < 0)
			return fctErrorMSG('Vous n\'êtes pas dans un lieu permettant ce type d\'action.', '?popup=1&m=Action_Deplacement');	
	
	//On récupère dans l'inventaire, les objets que l'utilisateur a sélectionné
		$i=0; $e=0;
		$objets=array();
		while( $item = $perso->getInventaire($db, $i++)){

				foreach($_POST['item'] as $id => $qte)
				{
					if(($id == $item->getInvId()) && ($qte != 0) && ($qte != null))
					{
						if(($qte <= $item->getQte()) && ($qte > 0))
						{
							$objets[$e++]['objet'] = $item;
							$objets[$e-1]['qte'] = $qte;
						}
					}
						
				}			
				
		}
		
		$continue=true;
		$remiseTotale=0.60;
	if(isset($_POST['march']))
	{
		$De = round(rand(1, 100));	
		$reussite =  $perso->getChancesReussite($db,"MRCH");
	
	
		$message = "(négo) ";
		if($De < $reussite)
		{
		//Calcul de la remise
			if($reussite != 0)
				$pourcentage = $reussite / 100;
			else
				$pourcentage = 0;
				
			$ajoutRemise = 10 * $pourcentage;				
			$remise = 60 + $ajoutRemise;	
			
			if($remise != 0)
				$remiseTotale = $remise / 100;
			else
				$remiseTotale = 0.60;	
			$pa = 15;
		}
		else
		{
			Member_He::add($db, 0, $perso->getId(), 'Recyclage',"La négociation a échoué." ,HE_AUCUN, HE_UNIQUEMENT_MOI);
			$perso->changePa  ("-", 15);
			$perso->setPa ($db, $perso->getPa());
			$continue=false;
		}	
	
	}
	if($continue==true)
	{
		//On parcours chaque objet, que l'on va enlever de l'inventaire après avoir donné les sous au bonhomme en fonction de sa compétence en marchandage
		$message = $message."Vous vendez:\n";
		foreach($objets as $item)
		{

			$prix = $item['objet']->getDbPrix();
			$qteInv = $item['objet']->getQte();
			$qteVente = $item['qte'];

			$prixVente = round($prix * $remiseTotale * $qteVente);
			
			$qteRestante = $qteInv - $qteVente;
			
			$perso->changeCash("+",$prixVente);
			$perso->setCash($db,$perso->getCash());
			
			if($qteRestante == 0)
			{
				$sql = 'DELETE FROM ' . DB_PREFIX . 'item_inv 
						WHERE inv_id='.$item['objet']->getInvId().'
						LIMIT 1';

			}
			else
			{
				$sql = 'UPDATE ' . DB_PREFIX . 'item_inv 
						SET inv_qte='.$qteRestante.'
						WHERE inv_id='.$item['objet']->getInvId().'';		
			}
			
			$db->query($sql,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);	
			$message = $message."-".$qteVente." item(s):".$item['objet']->getNom().", pour : ".fctCreditFormat($prixVente, true)."\n";

					
		
		
		
		
		}
		$perso->changePa  ("-", $pa);
		$perso->setPa ($db, $perso->getPa());
		Member_He::add($db, 0, $perso->getId(), 'Système',$message ,HE_AUCUN, HE_UNIQUEMENT_MOI);	


	}
	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}

?>