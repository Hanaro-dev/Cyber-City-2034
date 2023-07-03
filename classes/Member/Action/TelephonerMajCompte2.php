<?php
/** Gestion de l'action de mise à jour du répertoire d'un téléphone
*
* @package Member_Action
*/
class Member_Action_TelephonerMajCompte2 { 
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$idTel = $_POST['telephone'];
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(($item->getInvId() == $idTel) AND ($item->getIdProprio() == $perso->getId())){
				$telephone = $item;
			}
		}	
		if(!is_numeric($_POST['nip']))
			return fctErrorMSG('Ce NIP n\'est pas numérique.', '?popup=1&m=Action_Telephoner');
		
		
		echo $telephone->majCompte($db,$_POST['compte'],$_POST['nip']);	

		
	}
}