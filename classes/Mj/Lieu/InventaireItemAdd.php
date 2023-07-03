<?php
/** Gestion de l'inventaire du personnage
*
* @package Mj
*/

class Mj_Lieu_InventaireItemAdd
{
	public static function generatePage(&$tpl, &$session, &$account, &$mj)
	{
		$dbMgr = DbManager::getInstance(); //Instancier le gestionnaire
		$db = $dbMgr->getConn('game'); //Demander la connexion existante
		
		
		if(!isset($_POST['itemId']))
			return fctErrorMSG('Vous devez sélectionner un item.', '?mj=Perso_Inventaire&id=' . $_GET['id'],null,false);
		
		
		if(!isset($_GET['cid']))
        {
            //Trouver le nom technique du lieu
            $query = 'SELECT nom_technique'
                    . ' FROM ' . DB_PREFIX . 'lieu'
                    . ' WHERE id=:lieuId'
                    . ' LIMIT 1;';
            $prep = $db->prepare($query);
            $prep->bindValue(':lieuId',	$_GET['id'],	PDO::PARAM_INT);
            $prep->execute($db, __FILE__, __LINE__);
            $arr = $prep->fetch();
            $prep->closeCursor();
            $prep = NULL;
            
            $lieuTech = $arr['nom_technique'];
        }
        

        //Préparer toutes les requêtes utiles
        //Trouver le modèle de l'item
		$query = 'SELECT *'
				. ' FROM ' . DB_PREFIX . 'item_db'
				. ' WHERE db_id=:dbId'
				. ' LIMIT 1;';
		$prepSel = $db->prepare($query);

        //Déterminer si un objet instancié existe déjà
        if(isset($_GET['cid']))
        {
             $query = 'SELECT inv_id, inv_qte'
                    . ' FROM ' . DB_PREFIX . 'item_inv'
                    . ' WHERE inv_dbid=:dbId'
                        . ' AND inv_idcasier=:idCasier'
                    . ' LIMIT 1;';
        }
        else
        {
            $query = 'SELECT inv_id, inv_qte'
                    . ' FROM ' . DB_PREFIX . 'item_inv'
                    . ' WHERE inv_dbid=:dbId'
                        . ' AND inv_lieutech=:lieuTech'
                    . ' LIMIT 1;';
        }
		$prepSel2 = $db->prepare($query);

        //Mettre à jours la quantité de l'objet déjà existant
		$query = 'UPDATE ' . DB_PREFIX . 'item_inv'
				. ' SET inv_qte=:qte'
				. ' WHERE inv_id=:invId'
				. ' LIMIT 1;';
		$prepUpd = $db->prepare($query);

        //Créer l'objet déjà existant
        if(isset($_GET['cid']))
        {
		/*
            $query = 'INSERT INTO ' . DB_PREFIX . 'item_inv'
                    . ' (`inv_id`, `inv_dbid`, `inv_idcasier`, `inv_qte`, `inv_munition`, `inv_resistance`, `inv_remiseleft`, `inv_pn`)'
                    . ' VALUES'
                    . ' (NULL,		:dbId,		:idCasier,		:qte,		:munition,		:resistance,	NULL,	:pn);';
		*/
			$query = 'INSERT INTO ' . DB_PREFIX . 'item_inv'
					. ' (`inv_id`, `inv_dbid`, `inv_idcasier`, `inv_equip`,'
						. ' `inv_qte`, `inv_munition`, `inv_duree`,'
						. ' `inv_shock_pa`, `inv_shock_pv`, `inv_boost_pa`,`inv_boost_pv`,'
						. ' `inv_perc_stat_agi`, `inv_perc_stat_dex`, `inv_perc_stat_per`,'
						. ' `inv_perc_stat_for`, `inv_perc_stat_int`, `inv_resistance`,'
						. ' `inv_remiseleft`, `inv_pn`'
					. ' )'
					. ' VALUES'
					. ' ('
						. ' NULL, :dbId, :idCasier, :equip, :qte, :munition, :duree,'
						. ' :shockPa, :shockPv, :boostPa, :boostPv,'
						. ' :statAgi, :statDex, :statPer, :statFor, :statInt,'
						. ' :resistance, NULL, :pn'
					. ' );';
		}
        else
        {/*
            $query = 'INSERT INTO ' . DB_PREFIX . 'item_inv'
                    . ' (`inv_id`, `inv_dbid`, `inv_lieutech`, `inv_qte`, `inv_munition`, `inv_resistance`, `inv_remiseleft`, `inv_pn`)'
                    . ' VALUES'
                    . ' (NULL,		:dbId,		:lieuTech,		:qte,		:munition,		:resistance,	NULL,	:pn);';*/
			$query = 'INSERT INTO ' . DB_PREFIX . 'item_inv'
					. ' (`inv_id`, `inv_dbid`, `inv_lieutech`, `inv_equip`,'
						. ' `inv_qte`, `inv_munition`, `inv_duree`,'
						. ' `inv_shock_pa`, `inv_shock_pv`, `inv_boost_pa`,`inv_boost_pv`,'
						. ' `inv_perc_stat_agi`, `inv_perc_stat_dex`, `inv_perc_stat_per`,'
						. ' `inv_perc_stat_for`, `inv_perc_stat_int`, `inv_resistance`,'
						. ' `inv_remiseleft`, `inv_pn`'
					. ' )'
					. ' VALUES'
					. ' ('
						. ' NULL, :dbId, :lieuTech, :equip, :qte, :munition, :duree,'
						. ' :shockPa, :shockPv, :boostPa, :boostPv,'
						. ' :statAgi, :statDex, :statPer, :statFor, :statInt,'
						. ' :resistance, NULL, :pn'
					. ' );';
        }
		
		$prepIns = $db->prepare($query);

        
		foreach($_POST['itemId'] as $addItemId)
		{
			$prepSel->bindValue(':dbId',	$addItemId,	PDO::PARAM_INT);
			$prepSel->execute($db, __FILE__, __LINE__);
			$arr = $prepSel->fetch();
			
			
			if ($arr['db_regrouper']=='1')
			{
				$item_qte = $_POST['item' . $addItemId];
				$query_qte = 1;
				
				//Vérifier si le perso actuel possède déjà cet item, si oui: augmenter la qte.
				$prepSel2->bindValue(':dbId',		$addItemId,	PDO::PARAM_INT);
				if(isset($_GET['cid']))
                    $prepSel2->bindValue(':idCasier',	$_GET['cid'],	PDO::PARAM_INT);
                else
                    $prepSel2->bindValue(':lieuTech',	$lieuTech,	PDO::PARAM_STR);

                $prepSel2->execute($db, __FILE__, __LINE__);
				$arr2 = $prepSel2->fetch();
			
				if ($arr2 !== false)
				{
					//Augmenter la Qte
					$prepUpd->bindValue(':qte',		$arr2['inv_qte']+$item_qte,	PDO::PARAM_INT);
					$prepUpd->bindValue(':invId',		$arr2['inv_id'],			PDO::PARAM_INT);
					$prepUpd->execute($db, __FILE__, __LINE__);
					
					$query_qte = 0; //Ne pas ajouter d'item avec la requête INSERT ci-dessous
				}
				
			}
			else
			{
				$item_qte = 1;
				$query_qte = $_POST['item' . $addItemId];	
			}
			
			
			//Insérer de nouveau enregistrement
			$prepIns->bindValue(':dbId',	$addItemId,	PDO::PARAM_INT);
            
            if(isset($_GET['cid']))
                $prepIns->bindValue(':idCasier',	$_GET['cid'],	PDO::PARAM_INT);
            else
                $prepIns->bindValue(':lieuTech',$lieuTech,	PDO::PARAM_STR);

            $prepIns->bindValue(':qte',		$item_qte,	PDO::PARAM_INT);
/*			
			if($arr['db_soustype'] == 'arme_feu')
				$prepIns->bindValue(':munition',	0,	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':munition',	NULL,	PDO::PARAM_NULL);
			
			if(isset($arr['db_resistance']))
				$prepIns->bindValue(':resistance',	$arr['db_resistance'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':resistance',	NULL,	PDO::PARAM_NULL);
			
			if($arr['db_type'] == 'nourriture')
				$prepIns->bindValue(':pn',	$arr['db_pn'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':pn',	NULL,	PDO::PARAM_NULL);
*/			if($arr['db_type'] == 'arme' || $arr['db_type'] == 'defense')
				$prepIns->bindValue(':equip',	0,						PDO::PARAM_INT);
			else
				$prepIns->bindValue(':equip',	NULL,					PDO::PARAM_NULL);

			if($arr['db_soustype'] == 'arme_feu')
				$prepIns->bindValue(':munition',	0,							PDO::PARAM_INT);
			else
				$prepIns->bindValue(':munition',	NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_duree']))
				$prepIns->bindValue(':duree',		$arr['db_duree'],			PDO::PARAM_INT);
			else
				$prepIns->bindValue(':duree',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_shock_pa']))
				$prepIns->bindValue(':shockPa',		$arr['db_shock_pa'],		PDO::PARAM_INT);
			else
				$prepIns->bindValue(':shockPa',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_shock_pv']))
				$prepIns->bindValue(':shockPv',		$arr['db_shock_pv'],		PDO::PARAM_INT);
			else
				$prepIns->bindValue(':shockPv',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_boost_pv']))
				$prepIns->bindValue(':boostPv',		$arr['db_boost_pv'],		PDO::PARAM_INT);
			else
				$prepIns->bindValue(':boostPv',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_boost_pa']))
				$prepIns->bindValue(':boostPa',		$arr['db_boost_pa'],		PDO::PARAM_INT);
			else
				$prepIns->bindValue(':boostPa',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_perc_stat_agi']))
				$prepIns->bindValue(':statAgi',		$arr['db_perc_stat_agi'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':statAgi',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_perc_stat_dex']))
				$prepIns->bindValue(':statDex',		$arr['db_perc_stat_dex'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':statDex',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_perc_stat_per']))
				$prepIns->bindValue(':statPer',		$arr['db_perc_stat_per'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':statPer',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_perc_stat_for']))
				$prepIns->bindValue(':statFor',		$arr['db_perc_stat_for'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':statFor',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_perc_stat_int']))
				$prepIns->bindValue(':statInt',		$arr['db_perc_stat_int'],	PDO::PARAM_INT);
			else
				$prepIns->bindValue(':statInt',		NULL,						PDO::PARAM_NULL);

			if(isset($arr['db_resistance']))
				$prepIns->bindValue(':resistance',	$arr['db_resistance'],		PDO::PARAM_INT);
			else
				$prepIns->bindValue(':resistance',	NULL,						PDO::PARAM_NULL);

			if($arr['db_type'] == 'nourriture')
				$prepIns->bindValue(':pn',		$arr['db_pn'],					PDO::PARAM_INT);
			else
				$prepIns->bindValue(':pn',		NULL,							PDO::PARAM_NULL);
				
			if($query_qte>0)
				for($q=1; $q<=$query_qte; $q++)
					$prepIns->execute($db, __FILE__, __LINE__);
			
		}
		$prepSel->closeCursor();
		$prepSel = NULL;
		$prepSel2->closeCursor();
		$prepSel2 = NULL;
		$prepIns->closeCursor();
		$prepIns = NULL;
		
		//Retourner le template complété/rempli
        if(isset($_GET['cid']))
            $tpl->set('PAGE', 'Lieu_Inventaire&id=' . $_GET['id'] . '&cid=' . $_GET['cid']);
        else
            $tpl->set('PAGE', 'Lieu_Inventaire&id=' . $_GET['id'] );
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Mj/redirect.htm',__FILE__,__LINE__);
	}
}

