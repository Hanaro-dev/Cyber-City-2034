<?php
/** Gestion des options d'affichage
*
* @package Member
*/
class Member_Config_Affichage{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
	
		$query = 'SELECT skin, heitems
					FROM ' . DB_PREFIX . 'account
					WHERE id=' . $account->getId(). ';';
		$result = $db->query($query,__FILE__,__LINE__,__FUNCTION__,__CLASS__,__METHOD__);
		$rskin = mysql_fetch_array($result);
		
		//Lister les skins disponibles
		$skins = array();
		$dir2 = dir(SITE_PHYSICAL_PATH . 'tpl/');
		$counter=0;
		while ($url = $dir2->read()) {
			if (is_dir(SITE_PHYSICAL_PATH . 'tpl/' . $url) && !preg_match('/^(_common|\\.|\\.\\.)$/', $url)){
				$counter++;
				$skins[$counter]['name'] = $url;
				
				// Si le skin listé est celui actuellement utilisé
				if ($url == $rskin['skin']) 
					$skins[$counter]['set'] = true;
			}
		}
		$tpl->set('SKINS',$skins);
		
		//Créer le tableau de la liste des possibilités pour le HE
		$arr_values = array(5,10,15,20,25,30,40,50,75,100,125,150,175,200);
		$heitems = array();
		for ($i=0;$i<count($arr_values);$i++){
			$heitems[$i]['value'] = $arr_values[$i];
			if($rskin['heitems']==$arr_values[$i]){
				$heitems[$i]['set'] = true;
			}
		}
		$tpl->set('HEITEMS',$heitems);
		
		
		//Retourner le template complété/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Config/affichage.htm',__FILE__,__LINE__);
	}
}
/*
		}else{
			$query = "UPDATE " . DB_PREFIX . "account SET skin='" . $_POST["skinname"] . "', heitems='" . $_POST["heitems"] . "' WHERE user='" . $ACCOUNT_VAR["user"] . "';";
			db_query($query,__FILE__,__LINE__);
			
			echo "<script type=\"text/javascript\">window.parent.location.reload();</script>";
		}
*/
?>