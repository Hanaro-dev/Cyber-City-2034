<?php
/** Gestion de l'interface de l'action Téléphoner: Envoyer le message
*
* @package Member_Action
*/
class Member_Action_Telephoner2 { 
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{ 
		//Vérifications du post
		if(!isset($_POST['telephone']))
			return fctErrorMSG('Vous n\'avez pas sélectionné de téléphone.', '?popup=1&m=Action_Telephoner');
			// ^^ VOILA !!! C'est comme ca qu'on utilise la nouvelle méthode :) pas compliqué non ? Je te laisse t'ammuser à faire les autres :) :)
			
		if(empty($_POST['numero_destinataire']))
			return fctErrorMSG('Vous n\'avez pas composé de numéro de téléphone', '?popup=1&m=Action_Telephoner');
			
		if(empty($_POST['message']))
			return fctErrorMSG('Vous n\'avez pas entré de message', '?popup=1&m=Action_Telephoner');
		
		
		//récup des info  du l'appeleur
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(is_a($item, 'Member_ItemTelephone')){
				if($item->getInvId() == $_POST['telephone'])
				
				$telephoneAppeleur = $item;
			}
		}	
			
		echo $telephoneAppeleur->envoyerMessage($db,$_POST['numero_destinataire'],$_POST['message'],$_POST['anonyme']);

		
		
	
	
	
	
	
	
	
	
	
	
	
	}
}