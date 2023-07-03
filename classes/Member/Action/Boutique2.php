<?php
/** Gestion de l'interface d'une boutique
*
* @package Member_Action
*/
class Member_Action_Boutique2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		//Déclaration des variables pour cette action
		$pacost = array();
		$pacost["achat"] = 10;
		$pacost["nego"] = 20;
		$pacost["vol"] = 40;
		$msg = "";
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		
		//Valider si le lieu actuel est une boutique
		if(!$perso->getLieu()->isBoutique())
			return fctErrorMSG('Ce lieu n\'est pas une boutique.');
		
		
		//Valider si toutes les sélections nécésaires ont été faites
		
		if (!isset($_POST['achat_type']))
			return fctErrorMSG('Vous n\'avez pas sélectionné le mode d\'achat.', '?m=Boutique');
			
		if (!isset($_POST['pay_type']) && $_POST['achat_type']!='vol')
			return fctErrorMSG('Vous n\'avez pas sélectionné le mode de paiement.', '?m=Boutique');
			
		if ($perso->getPa() < $pacost[$_POST['achat_type']])
			return fctErrorMSG('Vous n\'avez pas assez de PA.', '?m=Boutique');
		
		
		//Valider les PR
		// ... Des items à acheter
		$i=0; $prTotal=0; $prixTotal=0; $itemsList=''; $achat = false;
		while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++)){
			if(isset($_POST['itema' . $item->getInvId()])){
				if(!is_numeric($_POST['itema' . $item->getInvId()]))
					return fctErrorMSG('Les quantités doivent être des chiffres.');
				
				if($_POST['itema' . $item->getInvId()]<0)
					return fctErrorMSG('Les quantités doivent être des chiffres positifs.');
				
				$_POST['itema' . $item->getInvId()] = round($_POST['itema' . $item->getInvId()]);
				
				$prTotal+= $item->getPr();
				$prixTotal+= $item->getBoutiquePrixVente(false)*$_POST['itema' . $item->getInvId()];
				
				
				//Items que le perso désire acheter
				if($_POST['itema' . $item->getInvId()]>0){
					
					//Générer la liste des items pour l'inclure dans le message de conclusion
					if(!empty($itemsList))
						$itemsList .= ',';
					$itemsList .=  $_POST['itema' . $item->getInvId()] . 'x' .$item->getNom();
					
					$achat=true;
				}

			}
		}
		//... Des items à vendre
		$i=0; $prixVenteTotal=0; $itemsVenteList=''; $vente=false;
		while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++)){
			if(isset($_POST['itemv' . $item->getInvId()])){
				if(!is_numeric($_POST['itemv' . $item->getInvId()]))
					return fctErrorMSG('Les quantités doivent être des chiffres.');
				
				if($_POST['itemv' . $item->getInvId()]<0)
					return fctErrorMSG('Les quantités doivent être des chiffres positifs.');
				
				$_POST['itemv' . $item->getInvId()] = round($_POST['itema' . $item->getInvId()]);
				
				$prTotal-= $item->getPr();
				$prixVenteTotal+= $item->getBoutiquePrixAchat()*$_POST['itemv' . $item->getInvId()];
				
				//Items que le perso désire vendre
				if($_POST['itemv' . $item->getInvId()]>0){
					
					//Générer la liste des items pour l'inclure dans le message de conclusion
					if(!empty($itemsVenteList))
						$itemsVenteList .= ',';
					$itemsVenteList .=  $_POST['itemv' . $item->getInvId()] . 'x' .$item->getNom();
					
					$vente = true;
				}
			
			}
		}
		if(($perso->getPrMax()-$perso->getPr()) < $prTotal)
			return fctErrorMSG('Vous n\'avez pas assez de PR.');
		
		
		
		
		
		if ($achat){
			switch ($_POST['achat_type']){
				case 'achat':
					$msg	= "Vous effectuez l'achat de " . $itemsList;
					$vol	= false;
					$achat	= true;
					break;
					
				case 'nego':
					$msg	= "Vous temptez la négociation de " . $itemsList;
					$vol	= false;
					$achat	= true;
					
					$chances_reussite = getChancesReussite($db, 'MRCH');
					$de = rand(0,100);
																					echo "<br />" . $de . "<" . $chances_reussite;
					if ($de < $chances_reussite) {//Reussite
						//Calculer le rabais
						$rabais		= rand(1,($perso->getCompRealLevel($db, 'MRCH')*5)+5); //De 1 à 65%
						$prixTotal	*= (100-$rabais)/100;
						$prixVenteTotal	*= 1+(100-$rabais)/100;
						$msg .= " et vous arrivez à obtenir une réduction de " . round($rabais,2) . "%, soit un prix total de " . $prixTotal . GAME_DEVISE . ".";
					}else{
						$msg .= " mais c'est un échec.";
					}
					break;
					
				case 'vol':
					$msg	= "Vous temptez le vol de " . $itemsList;
					$vol	= true;
					
					$chances_reussite = getChancesReussite($db, 'PCKP');
					$de = rand(0,100);
																					echo "<br />" . $de . "<" . $chances_reussite;
					if ($de < $chances_reussite) {//Reussite
						$achat		= true;
						$prixTotal	= 0;
						$msg 		.= " avec success.";
					}else{
						$achat	= false;
						$msg = " mais c'est un échec.";
					}
					break;
					
			}
		}
		
		$prixTotal -= $prixVenteTotal; //Soustraire le prix des ventes aux prix des achats.
		
		
		//Vérifier les fond disponible selon le mode de paiement
		if ($achat==true && $vol==false){
			if ($_POST['pay_type']=='cash'){
				//PAIEMENT CASH
				//Valider le montant
				if ($perso->getCash() < $prixTotal)
					return fctErrorMSG('Vous n\'avez pas assez d\'argent pour effectuer cette transaction.', '?m=Boutique');
				
				//Débiter le perso
				$perso->changeCash($db, (($prixTotal>0) ? '-' : '+'), $prixTotal);
					
				//Payer la caisse de la boutique
				$perso->getLieu()->changeBoutiqueCash($db, (($prixTotal>0) ? '+' : '-'), $prixTotal);
			}else{
				//PAIEMENT DIRECT
				if (!isset($_POST['cardid']))
					return fctErrorMSG('Vous n\'avez pas sélectionné de carte de guichet.', '?m=Boutique');
				
				if (!isset($_POST['nip']))
					return fctErrorMSG('Vous n\'avez pas entré de NIP.', '?m=Boutique');
				
				
				//Instancier la carte de guichet sélectionnée
				$i=0;
				while( $item = $perso->getInventaire($db, $i++)){
					if($item instanceof Member_ItemCartebanque){
						if($item->getInvId() == $_POST['cardid']){
							$CARTE = $item;
							break;
						}
					}
				}
				if (!isset($CARTE))
					return fctErrorMSG('Carte de guichet introuvable.', '?m=Boutique');
				
				
				//Instancier l'accès à la carte
				$query = 'SELECT *
							FROM ' . DB_PREFIX . 'banque_cartes
							WHERE carte_id=' . $item->getNoCarte() . ';';
				$result=$db->query($query,__FILE__,__LINE__);
				if (mysql_num_rows($result)==0)
					return fctErrorMSG('Carte de guichet est désactivée.', '?m=Boutique');
					
				$arr = mysql_fetch_assoc($result);
				$CARTE_ACCESS = new Member_Banquecarte($arr);
				if(!$CARTE_ACCESS->isValid())
					return fctErrorMSG('Carte de guichet est désactivée pour le moment.', '?m=Boutique');
				
				if($CARTE_ACCESS->getNip() != $_POST['nip'])
					return fctErrorMSG('NIP est erronné.', '?m=Boutique');
				
				//Instancier le compte relié à la carte
				$query = 'SELECT * 
							FROM ' . DB_PREFIX . 'banque_cartes
							LEFT JOIN ' . DB_PREFIX . 'banque_comptes ON (compte_banque = carte_banque AND compte_compte = carte_compte)
							WHERE carte_id = ' . $item->getNoCarte() . '
							LIMIT 1;';
				$result = $db->query($query, __FILE__, __LINE__);
				if (mysql_num_rows($result)==0)
					return fctErrorMSG('Le compte est innexistant.', '?m=Boutique');
					
				$arr = mysql_fetch_assoc($result);
				$COMPTE = new Member_Banquecompte($arr);
				
				if($COMPTE->getCash() < $prixTotal)
					return fctErrorMSG('Compte sans fond.', '?m=Boutique');
				
				
				
				
				$query = 'SELECT * 
							FROM ' . DB_PREFIX . 'banque_comptes
							WHERE	compte_banque=' . $perso->getLieu()->getBoutiqueNoBanque() . '
								AND compte_compte=' . $perso->getLieu()->getBoutiqueNoCompte() . '
							LIMIT 1;';
				$result = $db->query($query, __FILE__, __LINE__);
				if (mysql_num_rows($result)==0)
					return fctErrorMSG('Le compte de la boutique est innexistant.', '?m=Boutique');
					
				$arr = mysql_fetch_assoc($result);
				$COMPTE_BOUTIQUE = new Member_Banquecompte($arr);
				
				//Débiter le compte du perso
				$COMPTE->changeCash((($prixTotal>0) ? '-' : '+'), $prixTotal);
				$COMPTE->setCash($db);
				
				$COMPTE->add_bq_hist($db, '', 'SDPD', $prixTotal);
				
				//Payer le compte de la boutique
				$COMPTE_BOUTIQUE->changeCash((($prixTotal>0) ? '+' : '-'), $prixTotal);
				$COMPTE_BOUTIQUE->setCash($db);
				$COMPTE_BOUTIQUE->add_bq_hist($db, $COMPTE->getNoBanque() . '-' . $COMPTE->getNoCompte(), 'RCPD', 0, $prixTotal);
				
			}
			
		}
		
		
		
		
		//TOUT EST PAYÉ, IL FAUT MAINTENANT TRANSFÉRER CEUX-CI CORRECTEMENT.
		if ($achat){
			$i=0;
			while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++)){
				if(isset($_POST['itema' . $item->getInvId()])){
					$qte = $_POST['itema' . $item->getInvId()];
					if($qte>0){
						Member_Item::transfererItemVersPerso($db, $item, $perso, $qte);
					}
				}
			}
		}
		
		
		if ($vente){
			$i=0;
			while( $item = $perso->getLieu()->getBoutiqueInventaire($db, $i++)){
				if(isset($_POST['itemv' . $item->getInvId()])){
					$qte = $_POST['itemv' . $item->getInvId()];
					if($qte>0){
						Member_Item::transfererItemVersBoutique($db, $item, $perso->getLieu(), $qte);
					}
				}
			}
		}
		
		$perso->refreshInventaire($db);
		
		//Modifier les PA
		$perso->changePa('-', $pacost[$_POST['achat_type']]);
		$perso->setPa($db);
		
		//Copier le message dans les HE
		if(!empty($msg))
			Member_He::add($db, 0, $perso->getId(), 'boutique', $msg);
		
		
		//Rafraichir le HE
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/herefresh.htm',__FILE__,__LINE__);
	}
}
?>