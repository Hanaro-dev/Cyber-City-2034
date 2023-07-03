<?php
/**Editer le contenu d'une carte memoire
*
* @package Member_Action
*/

//RESTE A FAIRE:
//voir pour le cryptage retour :\
class Member_Action_EditerCarteMemoire {
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{

	//decomposition de la var crée pour savoir type et ip (ordi ou cartemem) + mise en var
	$memoireT = explode('-', $_POST['idtype']);	
	$type_mem = $memoireT[0];
	$idMemoire = $memoireT[1];
	//attribution du type d'apreil
	if($type_mem == "lect"){
	$typeMemoire = "Member_ItemOrdinateur";
	}elseif($type_mem == "cm"){
	$typeMemoire = "Member_ItemCarteMemoire";}
	else{
	//erreur
	}

		//récup des cartes lecteurs
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemTelephone')){
				if($item->getInvId() == $_POST['lecteur'])
				
				$lecteur = $item;}}
		
			//récup contenu des cartes mémoires ou lecteurs
			$i=0;
			while( $item = $perso->getInventaire($db, $i++)){
				if(is_a($item, $typeMemoire)){
					if($item->getInvId() == $idMemoire)
					$memoire = $item;
				}}
				
		//verif de tous les psot (numeric +  validité clef + existance d'un lecteur)
		if((!is_numeric($_POST['clef'])) AND ($_POST['clef'] != '' )){
			return fctErrorMSG('Ceci n\'est pas une clef de cryptage numérique', '?popup=1&m=Action_AfficherCartesMemoire');
		}elseif(( $_POST['clef_crypt'] != md5($memoire->getKey()) ) AND ($memoire->getKey() != NULL) AND ($memoire->getKey() != 0)){
			return fctErrorMSG('Le contenu de cette mémoire n\'a pas pu être décrypté avec cette clef', '?popup=1&m=Action_AfficherCartesMemoire');
		}elseif($memoire == NULL){return fctErrorMSG('Erreur avec ce lecteur de cartes', '?popup=1&m=Action_AfficherCartesMemoire');}
			//création d'une var pour dire au  htm si on peut éditer ou pas
			$write_auto = $memoire->getMcWrite();

			if( ($memoire->getMemorySize() == 0) or ($memoire->getMemorySize() == NULL) ){
				$write_auto = 0;
			}
			//CRYPTAGE RETOUR
			//$donnees_post_cm['inv_memoiretext'] = replace_specials($donnees_post_cm['inv_memoiretext'],'strip'); 

			
			
			

			$contenuMemoire = $memoire->getMemory();
			$var_idtype = $_POST['idtype'];
			$clef_crypt = $_POST['clef_crypt'];

			$lecteur = $_POST['lecteur'];
	
			$tpl->set("memoire",$memoire);	
			$tpl->set("idtype",$var_idtype);
			$tpl->set("write_auto",$write_auto);
			$tpl->set("clef_crypt",$clef_crypt);			
			$tpl->set("lecteur",$lecteur);
			$tpl->set("contenuMemoire",$contenuMemoire);

	//Retourner le template complété/rempli
	return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/editerCarteMemoire.htm',__FILE__,__LINE__);	
	}
}

?>	