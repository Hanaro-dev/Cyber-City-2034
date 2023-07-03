<?php




//static string  Member_Perso::convStatCodeToName (string $code)
echo "<br><strong>Test de convStatCodeToName</strong>";
$arrStatCode = array(
				array("AGI", 'Agilité'),
				array("DEX", 'Dextérité'),
				array("FOR", 'Force'),
				array("INT", 'Intelligence'),
				array("PER", 'Perception'),
				array("PSY", 'Psychisme'),
				array(123, null),
				array("abc", null),
				array(null, null)
				);
foreach($arrStatCode as $arr){
	$ret = Member_Perso::convStatCodeToName ($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', Attendu: '" . $arr[1] . "', recu: '" . $ret . "'";
}


//static string Member_Perso::convStatCodeToName (string $code)
echo "<br><strong>Test de convStatCodeToName</strong>";
$arrCompCode = array(
				array("AFE", 'Arme à feu'),
				array("AMU", 'Armurie'),
				array("CAC", 'Corps à corps'),
				array("CHI", 'Chimie'),
				array("CND", 'Conduite'),
				array("COF", 'Contrefacon'),
				array("CRO", 'Crochetage'),
				array("CYB", 'Cybernétique'),
				array("ELE", 'Électronique'),
				array("ESQ", 'Esquive'),
				array("FOU", 'Fouille'),
				array("FUR", 'Furtivité'),
				array("GEN", 'Génétique'),
				array("INF", 'Informatique'),
				array("MAR", 'Marchandage'),
				array("MEC", 'Mécanique'),
				array("MED", 'Médecine'),
				array("MET", 'Metallurgie'),
				array("PIP", 'Pick-pocket'),
				array("VAT", 'Vol'),
				array(123, null),
				array("abc", null),
				array(null, null)
				);
foreach($arrCompCode as $arr){
	$ret = Member_Perso::convCompCodeToName ($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', Attendu: '" . $arr[1] . "', recu: '" . $ret . "'";
}



//static void Member_Perso::convCompXpToLevel ( $xp)
echo "<br><strong>Test de convCompXpToLevel </strong>";
$lastLvl=null;
for($i=-200;$i<=15000;$i++){
	$ret = Member_Perso::convCompXpToLevel ($i);
	if ($ret != $lastLvl){
		echo "<br>XP: " . $i . ", Level: " . $ret;
		$lastLvl = $ret;
	}
}

//static int Member_Perso::convLevelToChance (int $level)
echo "<br><strong>Test de convLevelToChance</strong>";
$lastPerc=null;
for($i=-3;$i<=20;$i++){
	$ret = Member_Perso::convLevelToChance ($i);
	if ($ret != $lastPerc){
		echo "<br>XP: " . $i . ", Chance: " . $ret . "%";
		$lastPerc = $ret;
	}
}


//static int Member_Perso::convStatXpToLevel (int $xp)
echo "<br><strong>Test de convStatXpToLevel</strong>";
$lastLvl=null;
for($i=-1000;$i<=1000;$i++){
	$ret = Member_Perso::convStatXpToLevel ($i);
	if ($ret != $lastLvl){
		echo "<br>XP: " . $i . ", Level: " . $ret;
		$lastLvl = $ret;
	}
}








require('../_v5conn.inc.php');
$db = new MySQLConnection();

//static string memberLevel (object &$db, int $id)
echo "<br><strong>memberLevel (a venir)</strong>";



//Instancier un personnage
echo "<br><strong>Instanciation d'un personnage</strong>";

$result = $db->query("SELECT * FROM cc_perso WHERE id=5;", __FILE__, __LINE__);
$arr = mysql_fetch_assoc($result);

$perso = new Member_Perso($db, $arr, true, true);

if(is_a($perso,"Member_Perso"))
	echo "<br>OK";
else
	echo "<br>ERREUR";
var_dump($perso);

	
//int getCompRawXp (string $code)
echo "<br><strong>getCompRawXp</strong>";
foreach($arrCompCode as $arr){
	$ret = $perso->getCompRawXp($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', recu: '" . $ret . "'";
}

//int getCompRawLevel (string $code)
echo "<br><strong>getCompRawLevel</strong>";
foreach($arrCompCode as $arr){
	$ret = $perso->getCompRawLevel($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', recu: '" . $ret . "'";
}

//int getCompRealLevel (string $code)
echo "<br><strong>getCompRealLevel </strong>";
foreach($arrCompCode as $arr){
	$ret = $perso->getCompRealLevel($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', recu: '" . $ret . "'";
}


//int getChancesReussite (string $code)
echo "<br><strong>getChancesReussite</strong>";
foreach($arrCompCode as $arr){
	$ret = $perso->getChancesReussite($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', recu: " . $ret . "%";
}











//int getStatRawXp (string $code)
echo "<br><strong>getStatRawXp</strong>";
foreach($arrStatCode as $arr){
	$ret = $perso->getStatRawXp($arr[0]);
	echo "<br>Param: '" . $arr[0] . "', recu: '" . $ret . "'";
}

//int getStatRealXp (object &$db, string $code)
echo "<br><strong>getStatRealXp</strong>";
foreach($arrStatCode as $arr){
	$ret = $perso->getStatRealXp($db, $arr[0]);
	echo "<br>Param: '" . $arr[0] . "', recu: '" . $ret . "'";
}

die('<br>Tout les test effectués.');

/*


void changeCash (object &$db, char $plusMoins, float $montant)
void changePa (object &$db, char $plusMoins, float $nbrPa)
void emptyInventaire ()
array generateActionMenu (object &$db)
string getAvatar ()
float getCash ()

string getDescription ()
int getId ()
object Retourne getInventaire (object &$db, int $id)
object getLieu ()
string getNom ()
int getPa ()
int getPaMax ()
int getPn ()
int getPnMax ()
int getPr ()
int getPrMax ()
int getPv ()
int getPvMax ()
string getSexe ()

bool isAutonome ()
bool isConscient ()
bool isVivant ()
void setCash (object &$db, float $montant)
string setComp (object &$db, array $arr)
void setPa (object &$db, float $nbrPa)
string setStat (object &$db, array $arr)
*/
?>