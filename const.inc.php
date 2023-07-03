<?php
/**
 * Fichier de configuration du moteur de jeu.
 *
 * @author Francois Mazerolle <admin@maz-concept.com>
 * @copyright Copyright (c) 2009, Francois Mazerolle
 * @version 1.0
 * @package CyberCity_2034
 */


//Constantes de connexion à une base de données pour le jeu
define ('DB_HOST',		'localhost');
define ('DB_USER',		'balamweb');
define ('DB_PASS',		'TaLyNe07');
define ('DB_BASE',		'cc4');
define ('DB_PREFIX',		'cc_');



//Constantes de connexion à une base de données pour un forum
define ('DB_FORUM_HOST',	NULL); //Placer ce champs à NULL pour désactiver
define ('DB_FORUM_USER',	'balamweb');
define ('DB_FORUM_PASS',	'TaLyNe07');

define ('DB_FORUM_BASE',	'forum_cc4');
define ('DB_FORUM_PREFIX',	'phpbb_');
define ('DB_FORUM_NEWS_ID',     '2');
/*
define ('DB_FORUM_BASE',	'cybercity2034_forum');
define ('DB_FORUM_PREFIX',	'smf_');
*/

//Connexion à la base de données des redirecteurs email (serveur PostFix seulement)
define ('DB_EMAIL_HOST',	NULL); //Placer ce champs à NULL pour désactiver
define ('DB_EMAIL_USER',	'ccv4_postfix');
define ('DB_EMAIL_PASS',	'2zqcSw7UR5rrRecF');
define ('DB_EMAIL_BASE',	'postfix_alias_maz');
define ('DB_EMAIL_PREFIX',	'postfix_');



//CONSTANTES DE FORUM
define('FORUM_URL',		'https://forum-cc4.balam-web.fr/');
define('FORUM_BOARD_IDS',	'38');
define('FORUM_SUBJECT_LIMIT', 	'5');

//CONSTANTES SESSION

//Délais d'expiration (en minutes) d'une session
define ('SESSION_TIMEOUT',		45);

//Heure actuelle
define ('CURRENT_TIME',			time ());

//Active des spécifités pour ce jeu
define('GAME_IS_CYBERCITY', true);




//CONSTANTES D'ACCÉS AUX FICHIERS

//Ce qui va aprés les @ des emails.
define ('SITE_DOMAIN', 				'cc4.balam-web.fr');

//Chemin virtuel vers la racine du moteur de jeu
define ('SITE_VIRTUAL_PATH',		'https://cc4.balam-web.fr/');

//Chemin physique vers la racine du site
define ('SITE_PHYSICAL_PATH',		'/home/balamweb/domains/cc4.balam-web.fr/public_html/');

//Chemin de base des templates (pour les liens d'images)
// SKIN_ROOT'	-> D�fini dans index.php apr�s l'instanciation de la session

//Chemin physique vers les templates (pour inclusion des fichiers)
// SKIN_REAL'	-> D�fini dans index.php apr�s l'instanciation de la session




//CONSTANTES D'AFFICHAGE

//Charset � utiliser par d�faut sur le site
define ('SITE_CHARSET',						'UTF-8');

//Nom du skin � utiliser par d�faut
define ('SITE_DEFAULT_SKIN',					'tholus');

//Nom du skin de base (100% complet).
// Si un fichier n'existe pas dans un skin, on charge celui du skin de base.
define ('SITE_BASE_SKIN',					'dark_blue');

//Titre du jeu
define ('SITE_NAME',						'CyberCity 2034');

//Version du jeu
define ('SITE_VERSION',						' v4');

//Devise utilis�e dans le jeu
define ('GAME_DEVISE',						'Cr');

//D�calage temporel (en ann�e) par rapport � l'ann�e actuelle
define ('GAMETIME_DECAL',					34);

//Mode debug [true = affiche les erreurs, false = log les erreurs]
define ('DEBUG_MODE',						false);

error_reporting(E_ALL ^ E_STRICT);


//CONSTANTES DE JEU

//Lieu (Nom technique) du spawn innitial lors de l'inscription
define ('LIEU_DEPART',						'Transit.hosto');

//Consid�rer une porte tenue pendant X heures
define ('TENIRPORTE_TIMEOUT',				48);

//Lieu (Nom technique) vers lequel un perso est t�l�port�e force pour innactivit�
define ('INNACTIVITE_TELEPORT_LOCATION',	'Transit.inactifs');

//D�lais (en heures) avant qu'un perso soit d�plac� automatiquement vers le lieu des innactifs
define ('INNACTIVITE_TELEPORT_DELAY',		72);

//D�lais (en jours) avant qu'un perso soit effac�
define ('INNACTIVITE_DELETE_DELAY',			30);

//Lieu (Nom technique) d'innactivit� d�sir�. Ce lieu exclu les joueurs de la t�l�portation automatique pour inactivit�
define ('INNACTIVITE_VOLUNTARY_LOCATION',	'Transit.attentes');

//Nombre de PA � donner � un joueur � sa remise
define ('PA_PAR_REMISE',					50);

//Nombre de PPA simultan�ment ouverts par perso.
define ('PPA_MAX',							3);

//Nombre de niveau de comp�tences � attribuer � l'inscription
define ('INSCRIPTION_NBR_COMP',				16);

//Nombre de niveau maximal par comp�tences � attribuer � l'inscription
define ('INSCRIPTION_MAX_COMP',				6);


//CONSTANTES DE FONCTIONNEMENT INTERNE DU SYST�ME

//D�lais (en jours) d'expiration (supression d�finitive) des messages supprim�s
define("ENGINE_HE_EXPIRE"		, 30);

//Page  accessible par ?m= qui ne requiert une authentification mais pas un perso actif
define("ENGINE_ACCESS_WITHOUT_PERSO", 			'News'
												. ',CreerPerso'
												. ',CreerPerso2'
												. ',CreerPerso2Check'
												. ',CreerPerso3'
												. ',ModPerso'
												. ',DelPerso'
												. ',ModPerso2'
												. ',DelPerso2'
												. ',ModPersoCheck'
												. ',Config_Compte'
												. ',Config_Compte2'
												. ',Config_Affichage'
												. ',Config_Affichage2');



//define("WARNING_MESSAGE", "Voici la V4! Comme il avait d&eacute;j&agrave; &eacute;t&eacute; annonc&eacute;, il se doit d'y avoir un reset de tous les comptes. Prenez donc le temps de bien pr&eacute;parer l'inscription de votre personnage, car elles ne seront pas valid&eacute;es avant le 5 février.");

define('ENGINE_ARR_BANNED_IP',				'212.79.74.200');
