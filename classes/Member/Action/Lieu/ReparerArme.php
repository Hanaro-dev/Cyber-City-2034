<?php
/** Gestion de l'interface de réparation des armes
*
* @package Member_Action
*/
class Member_Action_Lieu_ReparerArme
{
	public static function generatePage(&$tpl, &$session, &$account, &$perso)
	{
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante	//BUT: Démarrer un template propre à cette page
		
		//Vérifier l'état du perso
		if(!$perso->isAutonome())
			return fctErrorMSG('Votre n\'êtes pas en état d\'effectuer cette action.');
		
		// Liste des armes défectueuses en inventaire
		$i=0;
		$arrItems = array();
		while( $item = $perso->getInventaire($i++)) {
			if($item instanceof Member_ItemArme) {
				if ($item->getResistance() < $item->getResistanceMax()) {
					$arrItems[]['item'] = $item;
				}
			}
		}	
		for($i=0; $i<count($arrItems); $i++)
		{
			$type = $arrItems[$i]['item']->getTypeTech(); 
			switch($type){
				case 'arme_lancee':
					$lvl=0;
					$comp = '';
					break;
				case 'arme_feu':
					$id = $perso->convCompCodeToId('armu');
					$lvl = $perso->getCompRealLevel($id);
					$comp = $perso->getCompName($id);
					break;
				case 'arme_blanche':
					$id = $perso->convCompCodeToId('forg');
					$lvl = $perso->getCompRealLevel($id);
					$comp = $perso->getCompName($id);
					break;
				case 'arme_cac':
					$id = $perso->convCompCodeToId('forg');
					$lvl = $perso->getCompRealLevel($id);
					$comp = $perso->getCompName($id);
					break;
				case 'arme_paralysante':
					$id = $perso->convCompCodeToId('armu');
					$lvl = $perso->getCompRealLevel($id);
					$comp = $perso->getCompName($id);
					break;
			}
			$compReq = ceil($arrItems[$i]['item']->getForce()/5);
			if ($compReq >12)
				$arrItems[$i]['compRequise'] = 'Cette arme n\'est pas réparable';
			else
				$arrItems[$i]['compRequise'] = $comp . ' ' . $compReq;
			
			if ($lvl * 5 >= $arrItems[$i]['item']->getForce())
				$arrItems[$i]['reparable'] = true;
			else 
				$arrItems[$i]['reparable'] = false;
		}	
		
		$tpl->set('ITEMS', $arrItems);	
		$tpl->set('LIEU', $perso->getLieu());

		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/Lieu/ReparerArme.htm',__FILE__,__LINE__);
	}
}
