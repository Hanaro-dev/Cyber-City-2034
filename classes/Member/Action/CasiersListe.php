<?php
/** Gestion d'une boutique par son propriétaire
*
* @package Member_Action
*/
class Member_Action_CasiersListe{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: Démarrer un template propre à cette page
		
		
		
		//LISTER TOUT LES CASIERS DU LIEU
		$i=0;
		while( $casier = $perso->getLieu()->getCasiers($db, $i++))
			$CASIERS[$i] = $casier;
		
		$tpl->set('CASIERS', $CASIERS);
		
		
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/casiersList.htm',__FILE__,__LINE__);
	}
}
?>