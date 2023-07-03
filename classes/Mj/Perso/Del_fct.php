<?php
/** Fonction additionnelles pour la suppression d'un perso
*
* @package Mj
*/

//suppression du perso si le MJ est sûr
if ($_GET['action'] == 'suppr')
	{

	
	foreach ($info_pj as $pj) { 
	/*
	//suppression des historiques de comptes bancaires,
		//chercher le compte correspondant à l'id du perso (voir si maintien)
		$query_affich_bh = "SELECT *
		FROM ".DB_PREFIX."banque_comptes
		WHERE idperso='".$pj['id']."';";
		$donnees_bh = db_query($query_affich_bh,__FILE__,__LINE__);
		while ($pj_compte = mysql_fetch_assoc($donnees_bh))
			{
			//requête de suppression des histos
				$query_suppr_bh = "DELETE
				FROM ".DB_PREFIX."banque_historique
				WHERE compte='".$pj_compte['compte']."';";
				db_query($query_suppr_cb,__FILE__,__LINE__);
			}
	
	//suppression des items
	$query_suppr_item = "DELETE
		FROM ".DB_PREFIX."item_inv
		WHERE inv_persoid='".$pj['id']."';";
	db_query($query_suppr_item,__FILE__,__LINE__);
	*/

	/*
	//suppression des logs de tel (voir si maintien)
	$query_suppr_tel = "DELETE
			FROM ".DB_PREFIX."log_telephone
			WHERE from_persoid='".$pj['id']."';";
	db_query($query_suppr_tel,__FILE__,__LINE__);
	*/
	
	//suppression des lieux bannis par le perso
	$query_suppr_ban = "DELETE
				FROM ".DB_PREFIX."lieu_ban
				WHERE persoid='".$pj['id']."';";
	db_query($query_suppr_ban,__FILE__,__LINE__);
	
	//suppression des connaissances
	$query_suppr_conn = "DELETE
			FROM ".DB_PREFIX."perso_connu
			WHERE persoid='".$pj['id']."';";
	db_query($query_suppr_conn,__FILE__,__LINE__);
	

	//suppression des méssages du HE (show=0)
	$query_update_he = "UPDATE 
				".DB_PREFIX."he_fromto
				SET show='0'
				WHERE persoid='".$pj['id']."';";
	db_query($query_update_he,__FILE__,__LINE__);
	
	
	//téléportation des items de l'inventaire au sol.
	$query_update_items = "UPDATE 
				".DB_PREFIX."item_inv
				SET inv_persoid=''
					inv_lieutech='".$pj['lieu']."'
				WHERE inv_persoid='".$pj['id']."';";
	db_query($query_update_items,__FILE__,__LINE__);
	
	//ajout de l'argent du compte banquaire au compte commun
		//chercher argent + calcul
		$query_affich_cb= "SELECT *
		FROM ".DB_PREFIX."banque_comptes
		WHERE idperso='".$pj['id']."';";
		$donnees_cb = db_query($query_affich_bh,__FILE__,__LINE__);
		while ($cb = mysql_fetch_assoc($donnees_cb))
			{
			$argent_compte = $argent_compte + $cb['cash'];
			
			//insertion de l'argent
			$query_update_cb ="UPDATE 
					".DB_PREFIX."banque_comptes
					SET cash=cash+".$argent_compte."
					WHERE id='1';";
			db_query($query_update_cb,__FILE__,__LINE__);
			}
			

	
	
	
	//téléportation de l'item "liasse de billet" dans l'endroit où le pj s'est fait méchement delete par le MJ sadique (à prendre au premier degrés ^^, il faut bien se détendre quand on code)
	$cash = str_replace(',','.',$pj['cash']);
	$query_instert_item_lieu = "INSERT INTO ".DB_PREFIX."cc_item_inv 
								(inv_dbid,inv_lieutech,inv_param)
								VALUES (
									'2',
									'".$pj['lieu']."'
									'".$cash ."'
								);";
			db_query($query_insert_item_lieu,__FILE__,__LINE__);	
		
	//suppression du compte bancaire
	$query_suppr_cb = "DELETE
				FROM ".DB_PREFIX."banque_comptes
				WHERE idperso='".$pj['id']."';";
	db_query($query_suppr_cb,__FILE__,__LINE__);
	
	//suppression du perso
	$query_suppr_pj = "DELETE
					FROM ".DB_PREFIX."perso
					WHERE id='".$pj['id']."';";
	db_query($query_suppr_pj,__FILE__,__LINE__);

	

	die("<script>location.href='/?mj=index';</script>");
	}
	}

?>