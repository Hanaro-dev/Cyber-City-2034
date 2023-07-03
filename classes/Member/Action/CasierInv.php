<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_CasierInv{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		if(!isset($_POST['id_casier']))
			return fctErrorMSG('Aucun casier sélectionné.', '?popup=1&m=Action_CasiersListe');
		
		$tpl->set('ID_CASIER', $_POST['id_casier']);
		
		
		//LISTER TOUT LES CASIERS DU LIEU
		$i=0;
		$found=false;
		while( $casier = $perso->getLieu()->getCasiers($db, $i++)){
			if($casier->getId()==$_POST['id_casier']){
				$found = true;
				break;
			}
		}
		
		if(!$found)
			return fctErrorMSG('Le casier #' . $_POST['id_casier'] . ' est introuvable.', '?popup=1&m=Action_CasiersListe');
		
		
		//PROTECTION: vérifier la protection du casier
		if($casier->getProtection() != NULL){
		
			//Protection par digipass
			if($casier->getProtection() == 'pass'){
				if(isset($_POST['pass']) && $_POST['pass'] != $casier->getPass())
					$tpl->set('WRONGPASS', true);
				
				if(!isset($_POST['pass']) || $_POST['pass'] != $casier->getPass())
					return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/casier_digipass.htm',__FILE__,__LINE__);
				
				$tpl->set('PASS', $_POST['pass']);
				
			//Protection par clef
			}elseif($casier->getProtection() == 'clef'){
				$accesOk = false;
				
				//Trouver la clé
				$i=0; $e=0;
				$arrClefs = array();
				while( $item = $perso->getInventaire($db, $i++)){
					if($item instanceof Member_ItemClef){
						$arrClefs[$e++] = $item;
						
						if(isset($_POST['clef']) && $item->getId() == $_POST['clef']){
							if($item->getCode() == $casier->getPass()){
								$accesOk = true;
								$tpl->set('CLEF', $_POST['clef']);
								break;
							}
						}
					}
				}
					
				if(!$accesOk){
					if(count($arrClefs)>0)
						$tpl->set('CLEFS', $arrClefs);
					return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/casier_clef.htm',__FILE__,__LINE__);
				}
				
			
			}else{
				 fctBugReport($db, 'Une protection d\'un casier n\'est pas prise en charge par le système.', array('CasierId:' . $casier->getId(), 'Protection:' . $casier->getProtection()), __FILE__, __LINE__);
			}
		}
		
		
		
		//LISTER L'INVENTAIRE DU CASIER
		$i=0; $e=0;
		$arr = array();
		while($item = $casier->getItems($db, $i++))
			$arr[$e++] = $item;
		
		if(count($arr)>0)
			$tpl->set('ITEMS', $arr);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/casierInv.htm',__FILE__,__LINE__);
	}
}
?>