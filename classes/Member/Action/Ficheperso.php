<?php
/** Gestion de l'interface des d�placement
*
* @package Member_Action
*/
class Member_Action_Ficheperso{
	public static function generatePage(&$tpl, &$db, &$session, &$account, &$perso)
	{	//BUT: D�marrer un template propre � cette page
	
		//G�n�rer la liste des comp�tances
		$arrComp=array(
			'ARMB','ARMC','ARMF','ARML','ARMU','ARTI','ATHL','CHIM',
			'CROC','CUIS','CYBR','DRSG','ELEC','ENSG','ESQV','EXPL','FORG',
			'FRTV', 'INFO','LNCR','MECA','MEDS','MRCH','PCKP','PLTG','PSYC'
			);
		$comp = array();
		$i=0;
		foreach($arrComp as $code){
			$comp[$i]['code']		= $code;
			$comp[$i]['nom']		= Member_Perso::convCompCodeToName($code);
			$comp[$i]['pcompxp']	= Member_Perso::convCompLevelToXp($perso->getCompRawLevel($code));
			$comp[$i]['ncompxp']	= Member_Perso::convCompLevelToXp($perso->getCompRawLevel($code)+1);
			$comp[$i]['lvl']		= $perso->getCompRawLevel($code);
			$comp[$i]['xp']			= $perso->getCompRawXp($code);
			$i++;
		}
		$tpl->set("COMP",$comp);
		
		
		
		//G�n�rer la liste des statistiques
		$arrStat=array('AGI','DEX','FOR','INT','PER');
		$stat = array();
		$i=0;
		foreach($arrStat as $code){
			$stat[$i]['code']		= $code;
			$stat[$i]['nom']		= Member_Perso::convStatCodeToName($code);
			$stat[$i]['pminxp']		= Member_Perso::convStatLevelToXp($perso->getStatRealLevel($db, $code)-1);
			$stat[$i]['pmaxxp']		= Member_Perso::convStatLevelToXp($perso->getStatRealLevel($db, $code));
			$stat[$i]['nminxp']		= Member_Perso::convStatLevelToXp($perso->getStatRealLevel($db, $code));
			$stat[$i]['nmaxxp']		= Member_Perso::convStatLevelToXp($perso->getStatRealLevel($db, $code)+1);
			$stat[$i]['lvl']		= $perso->getStatRealLevel($db, $code);
			$stat[$i]['xp']			= $perso->getStatRealXp($db, $code);
			$i++;
		}
		$tpl->set("STAT",$stat);
		
		
		//Retourner le template compl�t�/rempli
		return $tpl->fetch($account->getSkinRemotePhysicalPath() . 'html/Member/Action/ficheperso.htm',__FILE__,__LINE__);
	}
}


?>