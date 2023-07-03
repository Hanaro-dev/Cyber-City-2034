
<?php
/** 
 * Fichier CSS
 *
 * @author Francois Mazerolle <admin@maz-concept.com>
 * @copyright Copyright (c) 2009, Francois Mazerolle
 * @version 1.2
 * @package CSS
 */
header('Content-type: text/css; charset=utf-8');

$borderColor1 = '#BABAC6';
$bgColor1 = 	'#211610';
$borderColor2 = '#AA856E';
$bgColor2 = 	'#400F08';
$borderColor3 = '#9A4F16';
$bgColor3 = 	'#600806';
$valueColor = 	'#EEEEDD';
?>

/**
CE FICHIER EST SÉPARÉ COMME SUIT:
- Général ......................... ( Style global du site )
- Layout .......................... ( Positionnement des zones principales du site )
- Zones et Tableaux ............... ( Style génénal des tableaux/panel )
- Formulaires ..................... ( Style appliqués aux éléments des formulaires )
- Styles applicables aux textes ... ( Style de certains type de texte: Homme, Femme, HJ, IJ, etc. )

- Spécificités:
-- Visitor
-- Member
-- Member Actions

/* ############################################################### */
/* ### TYPOS ###*/

.title {
	text-transform: uppercase;
	text-align:center;
	color: #ae5f23;
	border-top: 1px solid #ae5f23; 
	font-size:14px;
	font-weight:bold;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	padding: 0px;
	margin-top:2px;
	line-height : 30px;
}

h4 {
	color: #ae5f23;
	font-size:15px;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	margin : 0;
	margin : 0px;
	padding : 0px;
	line-height : 25px;
	font-weight : 500;
}

.name {
	font-size: 13px;
	font-weight: bold;
	text-align : left;
	color : #a57838;
}

p {
margin: 0;
padding : 0;
font-size : 15px;
line-height : 20px;
color: #e8cd91;
text-align : justify;
}

	
#PPAcontainer td.name {
text-align : left;
}

td.txtStyle_valeur {
/*background-color : #281f16;*/
}

/* nb de requetes et temps de génération de la page*/
div.footer span, #footer span{

	border-bottom: 1px solid #ae5f23;
	padding-bottom : 10px;
	margin-bottom : 10px;
	width : 944px;
	display : block;
	font-size:6pt;
	color:#999999;
	display :none;
}

hr {
color: #a57838;
background-color: #a57838;
border : none;
height : 1px;
}

.value, td.value a {
margin: 0;
padding : 0;
font-size : 12px;
color: #e8cd91;
}

h6 {
margin: 0;
padding : 0;
font-size : 8px;
color:#999999;
}

* {
Scrollbar-Base-Color: #ae5f23;
Scrollbar-Arrow-Color: Black;
Scrollbar-Face-Color: #ae5f23;
Scrollbar-Track-Color: #13100b;
Scrollbar-Shadow-Color: #ae5f23;
Scrollbar-Highlight-Color: #ae5f23;
Scrollbar-3Dlight-color: #ae5f23;
Scrollbar-darkshadow-color; #ae5f23;
}

::-webkit-scrollbar {
 width: 10px;
 height: 10px;
 }
 
::-webkit-scrollbar-track-piece  {
background-color: #13100b;
}
::-webkit-scrollbar-thumb:vertical {
background-color: #ae5f23;
 border : 1px solid #13100b;
}
::-webkit-scrollbar-button:start:decrement,
 ::-webkit-scrollbar-button:end:increment  {
 height: 10px;
 display: block;
 background-color: #ae5f23;
 }
/* ############################################################### */
/* ### STYLES applicables au TEXTE */

.txtStyle_risque, .txtStyle_risque:hover
{
	text-decoration:none;
	font-weight:bold;
	color:#ae5f23;
}
.txtStyle_critique, .txtStyle_critique:hover
{
	text-decoration:none;
	font-weight:bold;
	color:#ff0000;
}
.txtStyle_grayed, .txtStyle_grayed:hover{
	text-decoration:none;
	color:#666;
}
.txtStyle_system, .txtStyle_system:hover {
	text-decoration:none;
	color: #ae5f23;
}
.txtStyle_homme, .txtStyle_homme:hover {
	text-decoration:none;
	color:#AAAAFF;
}
.txtStyle_femme, .txtStyle_femme:hover {
	text-decoration:none;
	color:#FFAAFF;
}
.txtStyle_autre, .txtStyle_autre:hover {
	text-decoration:none;
	color:#ae5f23;
}
.txtStyle_date, .txtStyle_date:hover {
	text-decoration:none;
	font-size:8pt;
	font-family: verdana;
	color: #EEEEDD;
}
.txtStyle_valeur, .txtStyle_valeur:hover {
	text-decoration:none;
	font-size: 10pt;
	color: #e8cd91;
}

.txtStyle_heHj, .txtStyle_heHj:hover {
	color:#a57838;
}

.txtStyle_heDesc, .txtStyle_heDesc:hover {
	color:#c39955;
}

/* ############################################################### */
/* ### GÉNÉRAL */

body {
	background-image:url('img/BG.jpg');
	background-repeat:repeat;
	background-attachment: fixed;
	color: #e8cd91;
	margin:0px;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	font-size: 12pt;
}

body a, span.fakelink {
	color: #EEEEDD;
	text-decoration:none;
	cursor:pointer;
}

body a:hover, span.fakelink:hover {
	color: #EEEEDD;
	text-decoration:underline;
}

.clearboth{
	clear:both;
}

.center{
	margin:0;
	padding:0;
	border-spacing:0px;
	text-align:center;
}

.infobulle
{
	position: absolute;
	display:none;
	background-color:#211610;
	padding: 4px 4px 4px 4px;
	border : 1px solid #ae5f23;
	z-index:100;
	text-align:justify;
	color : #e8cd91;
	font-size : 12px;
	line-height : 13px;
	width : 300px;
}

/* infobulles pur css */
a.info{  
position:relative;
z-index:24;
color: #ae5f23;
font-size:15px;
font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
margin : 0px;
padding : 0px;
line-height : 25px;
font-weight : 500;
text-decoration: none;
border-bottom : 1px dotted #ae5f23;

}
 
a.info:hover{
z-index:25;
color: #ae5f23;
text-decoration: none;
}
 
a.info span{
display: none;
}

a.info:hover span{
	position: absolute;
	display:block;
	width : 300px;
	background-color:#211610;
	padding:4px 4px 4px 4px;
	border : 1px solid #ae5f23;
	z-index:100;
	text-align:justify;
	color : #e8cd91;
	font-size : 12px;
	line-height : 15px;
}	



.hovermenu
{
	position: absolute;
	display:none;
	z-index:100;
}


/** Positionnement, style et taille par défaut d'un tableau
*/
div.tlb_center, table.tbl_center{

	font-size:11pt;
	width:945px;
}





}
#div_compte, #div_lieu {
margin-top : 50px;
}



/** Style du conteneur des boutons d'actions du formulaire
*/
div.send, td.send {
	font-size:10px;
	font-weight:bold;
	vertical-align:top;
	text-align:center;
	padding:2px;
}

/** Style générale d'une zone ( menu, he, panel, etc..)
*/
div.panel {

	/*background-color: #211610; */
	margin:0px;
	padding:0px;
	
}

div#ajaxLogin_error{
	border: 1px solid #ae5f23;
	background-color: #211610;
	display:none;
	color:#FF0000;
	font-weight:bold;
	width:600px;
}
div#ajaxLogin_plzwait1{

}
div#ajaxLogin_plzwait2{
width : 600px;
left: 50%;
margin-left : -300px;
}
div.plzwait_ombre{
	position:fixed;
	z-index:997;
	display:none;
	opacity:0.40;
	top:0px;
	left:0px;
	background-color:#000000;
	height:100%;
	width:100%;
}

div.plzwait_content{
	border: 1px solid #ae5f23;
	background-color: #211610;
	position:fixed;
	z-index:998;
	display:none;
	top:100px;
	width:600px;
}



/* ############################################################### */
/* ### FORMULAIRE */


input[type=radio], input[type=checkbox] {
margin : 0px;
padding : 0px;
color : #A41605;
font-size : 10px;
}

input[type="text"], input[type="password"], textarea, select, option {
	background-color: #211610;
	border: 1px solid #ae5f23;
}


input[type="button"], input[type="submit"] {
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	padding: 2px;
	font-size: 10px;
	color: #810503;
	background-color: #ae5f23;
	border : none;
	margin:1px;
	width:140px;
	font-weight : bold;
}
input[type="button"]:hover, input[type="submit"]:hover {
	border : none;
	background-color: #810503;
	color : black;
}


input[type="text"], input[type="password"] {
	font: bold 11px Trebuchet MS, Tahoma, Verdana, sans-serif;
	color: #CCCCCC;
	margin : 0px;
	padding: 2px;
	
}

input[type="text"]:hover, input[type="password"]:hover {

	color: #FFFFFF;
}

/*
input:disabled {
	color: #444;
	border-color: #444;
}
*/

textarea {
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	font-size:13px;
	background-color: #211610;
	border: 1px solid #ae5f23;
	color: #CCC;
	padding: 2px;
}
textarea:hover {
	color: #FFFFFF;
}

select {
	margin : 0px;
	color: #CCC;
}

option { /* Elements de la select list */
	border: 1px solid #ae5f23;
	background-color: #211610;
	padding:0px;
	margin:0px;
	padding-right:3px;
	color: #FFF;
}


abbr {
	border : none;}





/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ### S-P-É-C-I-F-I-C-I-T-É-S --- P-A-G-E-S : VISITEURS ### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */


div.visitor_titre{
	font-size:18pt;
	margin:20px 0px 20px 0px;
}


/* ### index_full */

div#eXTReMe
{
	display:inline;
}

img.index_full_imgAnnuaire {
	border:0;
	width:88px;
	height:31px;
}

div.index_full_erreur{
	background-color:#330000;
}

div.index_full_erreurName{
	background-color:#990000;
	color:#CCCCCC;
}

div.forumtext{
	text-align:center;
	font-size:8pt;
}

div.time_gametime, div.time_sessioncountdown{
	text-align:center;
}

div.footer p{
	font-size:8pt;
	line-height:9pt;
	margin:2px;
}


/* ### index_lite */


div.index_lite_erreur{
	background-color:#330000;
}

div.index_lite_erreurName{
	background-color:#990000;
	color:#CCCCCC;
}


/* ### VISITOR - Login */

div.login_panel{
	margin: 100px auto 0px auto;
	width:300px;
	border:0px;
}
div.login_panel div.content{
	width:200px;
	margin:10px auto;
}

div.login_lineUser, div.login_linePass, div.login_lineSubmit{
	clear:both;
	margin-right:0px;
}

div.login_lineSubmit{
	margin-top:10px;
}

div.login_col1User, div.login_col1Pass{
	float:left;
	width:35px;
}

div.login_col2User, div.login_col2Pass{
	padding-right:0px;
	text-align:right;
}

div.login_note{
	margin: 100px auto;
	width:400px;
}

div#login_navs_pub{
	border:#CCC;
	background-color:#FFF;
	margin:10px auto;
	width:500px;
	color:#000;
}
div#login_navs_pub div#navs{
	margin:0 auto;
	text-align:center;
	width:370px;
}
div#login_navs_pub div#navs div.nav{
	float:left;
	margin:0 30px;
}

/* ### VISITOR - login_wrong */

div.login_wrong_error{
	margin-top:100px;
	margin-bottom:40px;
}


/* ### VISITOR - About */

div.about{
	width:400px;
	margin-left:auto;
	margin-right:auto;
	padding-bottom:15px;
}
div.about_categorie{
	clear:both;
	font-weight:bold;
	text-decoration:underline;
	width:100%;
	margin-top:15px;
	text-align:center;
}

div.about_poste{
	float:left;
	width:185px;
	text-align:right;
}
div.about_nom{
	float:right;
	width:185px;
	margin-left:15px;
}

/* ### VISITOR - background & FAQ*/
div.background_contenu, div.regles_contenu{
	text-align:justify;
	margin : 0;
	padding : 0;
}

div.background_contenu p{
margin : 0;
padding : 0;
text-indent : 0;

}

div.background_contenu p:first-letter{
margin-left : 0;
text-indent : 0;
padding : 0;
}


div.background_chapitre, div.faq_chapitre{
	width:100%;
	text-align:center;
	font-size:14pt;
	font-weight:bold;
	margin-top:50px;
	margin-bottom:10px;
	background-image:url('img/chapter_sep.png');
	background-position:center bottom;
	background-repeat:no-repeat;
	height:55px;
}

div.faq_contenu{
	text-align:justify;
}
div.background_menu, div.faq_menu{
	padding:5px;
	text-align:center;
}
span.faq_highligh
{
	color: #EEEEDD;
	font-weight:bold;
}
div.background_warning, div.faq_warning{
	font-style:italic;
	font-size:9pt;
	margin-top:15px;
}

/* ### VISITOR - inscription1 */

div.inscription_info{
	margin-bottom:20px;
	text-align:justify;
}

div.inscription_info div.content
{
	margin:10px;
}

div.inscription_notice{
	font-size:8pt;
	text-align:center;
}

div.inscription_name{
	width:200px;
	float:left;
	margin:1px 5px 0px 1px;
	padding:5px;
}
div.inscription_value{
	padding-top:10px;
}

p.inscription_note{
	margin-top:0px;
	padding-left:5px;
	font-weight:normal;
	font-size:8pt;
	color:#CCBB77;
}

div.inscription_condition{
	min-height:150px;
}


/* ### VISITOR - main */

div.main_zoneAll{
	width:940px; /*740*/
}

div.main_zoneLogo{
	width:100%;
	text-align:center;
	margin:20px 0px 20px 0px;
}

div.main_zoneTxt{
	text-align:justify;
}

div.main_zoneCitation{
	margin-top:20px;
	font-size:8pt;
	right:0px;
	margin-right:30px;
	padding:5px;
}

div.main_zoneCitation span.aquo{
	font-size:15pt;
	margin:5px;
}

div.main_zoneCitationCitation{
	margin-left:15px;
	font-style:italic;
}


/* ### VISITOR - passRecover (1 & 2) */

div.passreco_panel{
	width:400px;
	margin-left:auto;
	margin-right:auto;
}

div.passreco_value{
	padding-bottom:10px;
}




/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ### S-P-É-C-I-F-I-C-I-T-É-S --- P-A-G-E-S : MEMBRES ### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */


/* ### ICONES ###*/

.shortcuts {
display : inline-block;
float : left;
width : 30px;
height : 30px;
background-color : #ae5f23;
border : none;
}

.shortcuts img{
border : none;
}


.shortcuts:hover {
background-color : #810503;
}

div#infopj h3 {
display : inline-block;
height : 30px;
color: #e8cd91;
font-size:15px;
font-variant: small-caps ;
padding : 5px 0 0 0;
margin : 0 0 0 10px;

}
div#infopj h4 {
display : inline-block;
float : left;
height : 30px;
width : 160px;
color:#e8cd91;
font-size:12px;
line-height : 18px;
padding : 0;
margin : -3px 0 0 10px;
}

div#infopj h5 {
color:#ae5f23;
font-size:10px;
}

/* ### AVATAR ###*/
#avatar {
width : 154px;
Height : 204px;
float : left;
}
#avatar img {
border : 2px solid #AE5F23;
float : right;

}

/* ### MEMBER - pub mp iframe */


div.pubMp{
	margin-top:20px;
	width:100%;
	text-align:center;
	z-index:1;
	display:none;
	}

/* ### MEMBER - abonnement */
table#abo_progress
{
	margin:0 auto;
}
table#abo_progress td.sep
{
	font-size:18pt;
	padding:0px 20px;
}

/* ### MEMBER - news */
div.news_panel1, div.news_panel2, div.news_panel3{
	width:400px;
	margin:50px auto 50px auto;
}

div.news_panel3
{
	padding-top:15px;
	text-align:center;
}

div.news_choix{
	float:right;
}



/* ### MEMBER - index */

div.member_index_topmenu{
	margin:auto 0px auto 0px;
	height:12px;
}


div.menuTab {
	text-decoration:none;
}



div.menu_action_panel
{
	font-size:12px;
	font-weight:bold;
	text-align:left;
	padding:2px 2px 15px;
	background-color: #211610;
	border : 1px solid #ae5f23;

}
div.menu_action_panel a{
	display: block;
	margin:0px;
	padding-left:5px;
	font-size: 10pt;
	text-decoration: none;
	text-align:left;
	color : #ae5f23;
}

div.menu_action_panel a:hover {
	color:#810503;
	text-decoration: none;
}


div.menu_action_panel a.menu_action_PPA { 
	margin-top:5px;
	
	font-weight:bold;
	text-align:center;
}
div.menu_action_panel a.menu_action_PPA:hover { 
	font-style: oblique;
	text-align:center;
	color:#FFFFFF;
	
}



div.member_index_topmenu_options{
	float:left;
}

/* ### RETRACTATION DE L ACTION (obsolète)### */
div.member_index_panel{
	height:auto;
	width:940px; /* div.panel ajoute (1px de bordure + 2px de padding)*2==6px */
	margin: auto;
}

div.member_index_panel img{
	float : left;
}

div.member_index_panel_title{
	float:left;
}

div.member_index_panel_button{
display : none;
float : right;
width : 940px;
height : 20px;
margin-bottom : 20px;
}

div.member_index_panel_button :hover{
}

div.member_index_panel_button a{
line-height:20px;
text-align:center;
vertical-align:middle;
display : block;
width : 940px;
height : 20px;
color : #ae5f23;
font-weight : bold;
font-size : 20px;
}
div.member_index_panel_button a:hover{
color : #810503;
text-decoration : none;
}


/* ### ONGLETS### */

div.member_contactMj_topMenu{
	top:0px;
	width:940px;
	margin: 0px auto 0px auto;
}
div.ongletOn, div.ongletOff
{
	float:left;
	width:145px;
	height : 20px;
	cursor:pointer;
	border: none;
	margin-bottom : -2px;
}
div.ongletOn{
	background-color: #ae5f23;
	border-top : 3px solid #ae5f23;	
}	
div.ongletOff{
	background-color: #a57838;
	margin-top : 3px;
}
div.ongletOn span, div.ongletOff span{
	display : block; 
	vertical-align : center;
	text-align : center;
	line-height : 20px;
	Font-size : 12px;
	color : #810503;
}

div.ongletOff:hover{
	background-color:#810503;
}	
div.ongletOff span:hover{
color : black;
}

/* ### MEMBER - ContactMJ */

div.member_contactMj_content{
	clear:both;
	border:2px solid #9A4F16;
	width:700px;
	height:500px;
	margin: 0px auto 0px auto;
	padding-top:15px;
}

div.member_contactMjMod_notice{
	margin: 15px auto 15px auto;
	text-align:center;
	font-weight:bold;
	font-style:italic;
}

div.member_contactMjMod_messageContainer{
	text-align:center;
}


/* ### MEMBER - ContactMjMod */

div.member_contactMjMod_name{
	float:left;
	width:150px;
}


/* ### MEMBER - DelPerso */

div.member_delPerso{
	font-size:16pt;
}


/* ### MEMBER - CreerPerso */
div.member_creerPerso_text{
	text-align:justify;
}


/* ### MEMBER - CreerPerso2 */

span.member_creerPerso2_refusPanel{
	margin:0px auto 0px auto;
}


/* ### MEMBER - He Header */

/* ############################################################### */
/* ### HISTORIQUE DES ÉVÈNEMENTS */



/* ### Header du HE */

div.member_he_toplink{
	float:right;
}
#he_header {
	width:944px;
	
	margin:0;
	font-size:12pt;

}

#he_header_droite{
	 width:794px;
	 float:right;
	 margin-top : 10px;
	 height: 70px;
	 border-top: 1px solid #ae5f23; 
}

#he_header_gauche {
	float:left;
	width:150px;
	overflow: auto;
	margin-top : 10px; 
	height: 70px;
	border-top: 1px solid #ae5f23; 

}
#he_header_gauche  p{
	font-size: 10px;
	color : #ae5f23;
}
#he_header_gauche  a{
	color : #ae5f23;
}
#he_header_gauche  a:hover{
	color : #810503;
}
#he_size_info {
	font-size: 14px;
	color : #ae5f23;
	font : verdana;
}


#he_header_droite_pub{
	float:right;
}


table.member_he_header_bar {
	width:100%;
	margin:10px 0px 10px 0px;
	height:10px;
	border : 1px solid #ae5f23;
}
td.member_he_header_bar_full{
	background-color:#810503;
}

td.member_he_header_bar_empty{
	background-color:#ae5f23;
}




/* ### Items du HE */

div.he_leftbar{
	float:left;
	width:150px;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	border-right:2px solid #ae5f23;
	height : 30px;
	overflow : hidden;
	font-size:12px;
	line-height : 14px;
	font-weight:bold;
	text-align:left;
}
div.he_leftbar:hover{
height : auto;
}

div.he_rightbar{
	height : auto;
	float:left;
	margin-left:-2px;
	padding-left:5px;
	border-left:2px solid #ae5f23;
	width:781px;
	padding-bottom : 15px;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	font-size:13px;
	vertical-align: top;
}
span.color_date {
	font-size:10px;
	color:#ae5f23;
	display:inline;
}

span.member_he_item_de, span.member_he_item_a{
	color:#ae5f23;
	margin-left : 5px;
}

div.member_he_item_de_liste, div.member_he_item_a_liste{
	float : right;
	text-align : right;
	margin-right : 5px;
	height : auto;
}

.he_item{
	height : auto;
	margin: auto;
	width:944px;
	border-top:1px solid #ae5f23;
	/*overflow:auto;*/
}

/* Style spéficique selon le type de message */
div.he_type_parlerbadge{
	border:1px dashed #995555;
}




img.imgbg { /* INFO-BULLE, cadrage de l'image */
	padding:3px;
	margin-right:5px;
	text-align:left;
}



/* ############################################################### */
/* ### INVENTAIRE */


#debug {
display : none;
}
/*Afficher un icone lorsque le curseur passe au dessus d'une zone draggable, optionnel*/
.dragable{ /*IE*/
	position:absolute;
	cursor:pointer;
} 
.dragable:hover{ /*FF*/
	cursor:pointer;
	background-color: #ae5f23;
} 
.dropable_off{
	border:0px;
	margin-left:auto;
	margin-right:auto;
}
.dropable_on{
	border:1px solid gray;
	background-color:#810503;
	opacity: 0.5;
	/* filter: alpha(opacity=50); */
	margin-left:auto;
	margin-right:auto;
}


div.inv_fiche { /*Anciennement #subcenter*/
	
	border: 1px solid #ae5f23;
	background-color: #1e1812;
	font-size:10pt;
	width:944px;
}
#dz_jeter, #dz_consommer {
}

#silhouette{
float : right; 
width:364px; 
height : 465px;
border-left : 1px solid #ae5f23;
}

#silhouette .name {
 border-bottom : solid 1px #A45920;}
 
 
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ### S-P-É-C-I-F-I-C-I-T-É-S --- P-A-G-E-S : MEMBRES ACTIONS ### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */
/* ############################################################### */


div.member_action_panel{
	width:944px;
}
div.member_action_persoContainer, div.member_action_itemContainer{
	overflow:auto;
}


/* ############################################################### */
/* ### ITEM - MENOTTER */

div.member_action_menotter_left{
	float:left;
	width:300px;
}
div.member_action_menotter_right{
	float:right;
	width:300px;
}

span.member_action_menotter_aucunItem{
	font-style:italic;
}

textarea.member_action_menotter_msg{
	width:550px;
	height:75px;
}


/* ############################################################### */
/* ### ITEM - SAC */

div.member_action_sac_left{
	width:auto;
	margin : auto 0 auto 0;
	display : inline-block;
}

div.member_action_sac_right{
	width:auto;	
	height : auto;
	margin : 0;
	padding : 0;
	display : inline-block;
}

div.member_action_sac_linkoff
{
	border-left:4px solid #ae5f23;
	cursor:pointer;
	padding-left:10px;
}

div.member_action_sac_linkoff:hover{
	background-color:#ae5f23;
}

div.member_action_sac_linkon{
	background-color:#ae5f23;
	padding-left:10px;
	font-weight:bold;
}

div.member_action_sac_itemContainer{
	border:4px solid #ae5f23;
	padding:10px;
}

/* ############################################################### */
/* ### ITEM - FICHE PERSO */


table#member_action_fichecomp, table#member_action_fichestat{
}


td.normal, td.bonus, td.malus, td.invisible, td.current  {
width : 24px;
height : 18px;
font-size: 8pt; 
font-family:courier new;
color :transparent;
border : solid 1px #a57838;
padding : 0px;
margin : 0px;
}

td.normal {background-color : #A45920; border : inset 1px #a57838;}
td.current{font-size: 10pt; color: #e8cd91; font-weight : bold; border : 1px inset #a57838;}
td.bonus {background-color : #003300; border: 1px inset #a57838;}
td.malus {background-color : #810503; border : 1px outset #a57838;}
td.invisible {background-color : transparent; border : outset 1px #a57838;}


td.CompBar_full
{
	height : 3px;
	background-color :#033500;
	border : solid 1px #a57838;
	border-right : none;
	
}

td.CompBar_empty
{
	height : 1px;
	background-color : #a57838;
	border : solid 1px #a57838;
	border-left : none;
}


.StatBonusBar_empty, .StatBonusBar_full, .StatMalusBar_empty, .StatMalusBar_full{
	height : 1px;
	border : solid 1px #a57838;
}

.StatBonusBar_empty
{
	background-color : #a57838;
	border-left : none;
}

.StatBonusBar_full
{	
	background-color : #033500;
	border-right : none;
}

.StatMalusBar_empty
{
	background-color : #a57838;
	border-right : none;
}

.StatMalusBar_full
{	height : 1px;
	background-color : #820503;
	border-left : none;
}


td.lvl
{
	height:18px;
	width:25px;
	border : 1px solid #a57838;
	text-align:center;
}
.XP {
font-size:8pt;
font-family:courier new;
color: #e8cd91;}

.caracs {
width : 460px; 
margin-left : 10px; 
display : inline-bock;
float : left;
}


/* ############################################################### */
/* ### LAYOUT */

div#site
{
	width:980px;
	margin:0 auto;
}
div#content
{
	width:944px;
	padding-left:18px;
	padding-right:18px;
	background-image:url('img/background.jpg');
	background-repeat:repeat-y;
}



div#actionPanel
{
	width:944px;
	float : left;

}

div#actionPanelContent
{
	width:944px;
	float : left;
	}

.panel member_index_panel 
{
	width:944px;
}

div#header
{
	position:relative;
	width:980px;
	height:161px;
	background-image:url('img/header.png');
}

div#menu
{
	width:944px;
	height:35px;
	padding-bottom:15px;
}
 div.bouton
{
	float:left;
	display:block;
	width:102px;
	height:23px;
	padding:8px;
	background-image:url('img/bouton.jpg');
	text-transform: uppercase;
	text-align:center;
	color:#ae5f23;
	font-size:14px;
	font-weight:bold;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
}

div.bouton:hover
{
	cursor:pointer;
	color:#810503;
}



div#revision
{
	position:absolute;
	left:234px;
	top:132px;
	width:53px;
	text-align:center;
	font-family:arial;
	color:#d3b697;
	font-size:8px;
}
div#aboutus
{
	position:absolute;
	left:30px;
	top:135px;
	width:80px;
	text-align:center;
	font-size:12px;
}
div#aboutus a
{
	color : #999;
}

div#modedebug
{
	position:absolute;
	left:325px;
	top:140px;
	color:#F00;
	font-size:8px;
	font-weight:bold;
}

div#infos
{
	position:absolute;
	left:320px;
	top:16px;
	width:95px;
	height:65px;
	font-size:12px;
	color : #c8c8d2;
	font-type : verdana, sans-serif;
}

div#foruminfos
{
	position:absolute;
	left:465px;
	top:0px;
	width:475px;
	height:150px;
	text-align : center;
}
div.forumtitre
{
    font-weight:bold;
	color:#222;
	font-family: Times, serif;
	font-size:14pt;
	line-height : 30px;
}
div.forumsujet a
{
    font-size:12pt;
	font-family: Times, serif;
	color:#333;
}
div.forumsujet a:hover
{ 
    font-weight : bold;
	text-decoration : none;

}
hr.forumsep
{
	display:none;
}

}
 div#footernote
{
	font-size:10px;
}
div#links
{
	margin:0 auto;
	text-align:center;
}
div#realfooter
{
	width:980px;
	height:17px;
	background-image:url('img/footer.jpg');
}

/* ### TOP */
div#top {
width : 944px;
height : 200px;

}
div#historique {
float : left;
width : 944px;


}
div#infopj {
width : 400px;
height : 200px;
float : left;
}

div#action_immediate {
width : 544px;
float : right;
line-height : 13px;
}
div#action_immediate textarea{
font-size : 12px;
}

#action_immediate .button{
width : 100px;
height : 14px;
line-height : 14px;
margin : 0;
float : right;
padding : 0;
}

#actionImmediate {
width : 538px;
height : 50px;
}

div#parler {
width : 544px;
float : left;
margin-top : 4px;
}

div#parler {
width : 544px;
float : left;
line-height : 0px;
}



div#parler .button{
width : 100px;
height : 14px;
line-height : 14px;
margin : 0 148px 0 0;
padding : 0px;
float : right;
}

div#parler textarea{
width : 390px;
height : 137px;
float : left;
margin : 0px;
font-size : 12px;
}



div#list_perso{
width : 140px;
height : 143px;
float : right;
overflow : auto;
line-height : 10px;
font-size : 14px;
margin-left : 5px;
}




/* ############################################################### */
/* ### WTF à FMAZ */

.mj_left, .mj_right { 
border : solid 1px #ae5f23;
margin-bottom: 35px;
margin-left: 35px;
margin-right: 35px;
margin-top: 35px;
width: 300px;

 }

}
div.titre
{
	font-size:10px;
	color:#666;
}
div.propname
{
	clear:both;
	float:left;
	font-size:12px;
	padding-left:10px;
	color:#c8c8d2;
}
div.valeur
{
	padding-left:12px;
	float:left;
	font-size:12px;
	font-weight:bold;
	color:#c8c8d2;
}
div#infos div.sep
{
	clear:both;
	width:100%;
	height:6px;
}

table { 
border-spacing: 0px;

 }
 
/*SCROLLBARS*/

.scrollgeneric {
line-height: 1px;
font-size: 1px;
position: absolute;
top: 0; left: 0;
}

.vscrollerbase {
width: 1px;
margin-left : -3px;
z-index : 0;

background-color: #ae5f23;
}
.vscrollerbar {
width: 7px;
margin-left : -3px;
z-index : 0;

background-color: #ae5f23;
}

.hscrollerbase {
height: 10px;
background-color: white;

}
.hscrollerbar {
height: 10px;
background-color: black;
}

.scrollerjogbox {
width: 10px;
height: 10px;
top: auto; left: auto;
bottom: 0px; right: 0px;
background-color: gray;
}
