<?php
/** Gestion de l'action de mise à jour du répertoire d'un téléphone
*
* @package Member_Action
*/
class Member_Action_TelephonerMajMemoire2{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{
		$idTel = $_POST['idtelephone'];
		$i=0;
		while( $item = $perso->getInventaire($db, $i++)){

			if(($item->getInvId() == $idTel) AND ($item->getIdProprio() == $perso->getId())){
				$telephone = $item;
				}
			}
	$a=0;
	while($a<$telephone->getMemorySizeMax()){
		$nom_var_nom = 'nom_'.$a;
		$nom_var_no = 'no_'.$a;
		
			$nom[$a]=$_POST[$nom_var_nom];
			$no[$a]=$_POST[$nom_var_no];
		$a++;
		}
			
	echo $telephone->majRepertoire($db,$nom,$no);
}
}
?>