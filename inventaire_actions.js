var inventaire_actions_version = 7;

//Fonction générales
function fail(){
	$('request_msg').style.display="block";
	$('request_msgtxt').innerHTML="La requête à échouée, veuillez ré-essayer plus tard.";
}

function showplzwait(){
	$('plzwait1').style.display="block";
	$('plzwait2').style.display="block";
	$('request_msgtxt').innerHTML="Veuillez patienter...";
	$('request_msg').style.display="none";
}
function hideplzwait(){
	$('plzwait1').style.display="none";
	$('plzwait2').style.display="none";
}



//CONSOMMER
function conso(id){
	
	
	showplzwait();
	
	var dg = FindItem(id);
	
	var myAjax = new Ajax.Request(
			'?popup=1&m=Action_InventaireConso', 
			{
				method: 'post', 
				parameters: 'id='+dg.id, 
				onComplete: conso_confirm,
				onFailure: fail
			});
}
function conso_confirm(originalRequest){
	
	var rval= originalRequest.responseText;
	var params=rval.split("|");
	
	if (params[1]=="OK"){
		
		var dg		= FindItem(params[0]);
		var fiche	= FindItemFiche(params[0]);
		var conso	= FindDz('consommer');
		window.parent.document.getElementById('perso_pr').innerHTML=params[3];
		window.parent.document.getElementById('perso_pa').innerHTML=params[2];
		
		
		//Rétracter la fiche vers l'ancienne position de l'item
		var pos1 = new centerItemIntoItem(dg, dg.baseDz);
		fiche.moveResize(dg.id, pos1.x, pos1.y, 0, 0, false, 10, 20);
		
		//Effectuer le changement de Dz et de Menu
		fiche.isAffiche = false;
		
		
		//Déplacer l'item vers sa nouvelle Dz
		var pos2 = new centerItemIntoItem(dg, conso);
		dg.moveTo(dg.id, pos2.x, pos2.y, false, 10, 20);
		
		//Lorsque le déplacement est terminé, rafraichir la fenêtre pour afficher un inventaire sans l'item jeté
		setTimeout("window.location.reload()", 20*10);
		
		hideplzwait();
	}else{
		$('request_msg').style.display="block";
		$('request_msgtxt').innerHTML=unescape(params[1]);
	}
}




//EQUIPER
function equiper(id){
	showplzwait();
	
	var dg = FindItem(id);
	
	var myAjax = new Ajax.Request(
			'?popup=1&m=Action_InventaireEquiper', 
			{
				method: 'post', 
				parameters: 'id='+dg.id, 
				onComplete: equiper_confirm,
				onFailure: fail
			});
}
function equiper_confirm(originalRequest){
	
	var rval= originalRequest.responseText;
	var params=rval.split("|");
	
	if (params[1]=="OK"){
		var dg		= FindItem(params[0]);
		var fiche	= FindItemFiche(params[0]);
		window.parent.document.getElementById('perso_pa').innerHTML=params[2];
		window.parent.document.getElementById('perso_pr').innerHTML=params[3];
		
		//Rétracter la fiche vers l'ancienne position de l'item
		var pos1 = new centerItemIntoItem(dg, dg.baseDz);
		fiche.moveResize(dg.id, pos1.x, pos1.y, 0, 0, false, 10, 20);
		
		//Effectuer le changement de Dz et de Menu
		dg.baseDz = FindDz(dg.equipType);
		fiche.modAction('equiper','ranger','Ranger');
		fiche.isAffiche = false;
		
		//Déplacer l'item vers sa nouvelle Dz
		var pos2 = new centerItemIntoItem(dg, dg.baseDz);
		dg.moveTo(dg.id, pos2.x, pos2.y, false, 10, 20);
		
		hideplzwait();
	}else{
		$('request_msg').style.display="block";
		$('request_msgtxt').innerHTML=unescape(params[1]);
	}
}



//RANGER
function ranger(id){
	showplzwait();
	
	var dg = FindItem(id);
	
	var myAjax = new Ajax.Request(
			'?popup=1&m=Action_InventaireRanger', 
			{
				method: 'post', 
				parameters: 'id='+dg.id, 
				onComplete: ranger_confirm,
				onFailure: fail
			});
}
function ranger_confirm(originalRequest){
	
	var rval= originalRequest.responseText;
	var params=rval.split("|");
	
	if (params[1]=="OK"){
		var dg		= FindItem(params[0]);
		var fiche	= FindItemFiche(params[0]);
		window.parent.document.getElementById('perso_pa').innerHTML=params[2];
		window.parent.document.getElementById('perso_pr').innerHTML=params[3];
		
		//Rétracter la fiche vers l'ancienne position de l'item
		var pos1 = new centerItemIntoItem(dg, dg.baseDz);
		fiche.moveResize(dg.id, pos1.x, pos1.y, 0, 0, false, 10, 20);
		
		//Effectuer le changement de Dz et de Menu
		dg.baseDz = FindDz(dg.id);
		fiche.modAction('ranger','equiper','Équiper');
		fiche.isAffiche = false;
		
		//Déplacer l'item vers sa nouvelle Dz
		var pos2 = new centerItemIntoItem(dg, dg.baseDz);
		dg.moveTo(dg.id, pos2.x, pos2.y, false, 10, 20);
		
		hideplzwait();
	}else{
		$('request_msg').style.display="block";
		$('request_msgtxt').innerHTML=unescape(params[1]);
	}
}



//JETER
function jeter(id){
	
	if(!confirm("Jeter cet item ?"))
		return;
	
	showplzwait();
	
	var dg = FindItem(id);
	
	var myAjax = new Ajax.Request(
			'?popup=1&m=Action_InventaireJeter', 
			{
				method: 'post', 
				parameters: 'id='+dg.id, 
				onComplete: jeter_confirm,
				onFailure: fail
			});
}
function submitJeterForm(url, itemid, qte){
	showplzwait();
	
	var myAjax = new Ajax.Request(
			url,
			{
				method: 'post', 
				parameters: 'id='+itemid+'&askQte='+qte, 
				onComplete: jeter_confirm,
				onFailure: fail
			});
}
function jeter_confirm(originalRequest){
	
	var rval= originalRequest.responseText;
	var params=rval.split("|");
	
	if (params[1]=="OK"){
		
		var dg		= FindItem(params[0]);
		var fiche	= FindItemFiche(params[0]);
		var jeter	= FindDz('jeter');
		window.parent.document.getElementById('perso_pa').innerHTML=params[2];
		window.parent.document.getElementById('perso_pr').innerHTML=params[3];
		
		
		//Rétracter la fiche vers l'ancienne position de l'item
		var pos1 = new centerItemIntoItem(dg, dg.baseDz);
		fiche.moveResize(dg.id, pos1.x, pos1.y, 0, 0, false, 10, 20);
		
		//Effectuer le changement de Dz et de Menu
		fiche.isAffiche = false;
		
		
		//Déplacer l'item vers sa nouvelle Dz
		var pos2 = new centerItemIntoItem(dg, jeter);
		dg.moveTo(dg.id, pos2.x, pos2.y, false, 10, 20);
		
		//Lorsque le déplacement est terminé, rafraichir la fenêtre pour afficher un inventaire sans l'item jeté
		setTimeout("window.location.reload()", 20*10);
		
		hideplzwait();
	}else{
		$('request_msg').style.display="block";
		$('request_msgtxt').innerHTML=unescape(params[1]);
	}
}



//CHARGER DE MUNITION
function charger(id){
	showplzwait();
	
	var dg = FindItem(id);
	
	var myAjax = new Ajax.Request(
			'?popup=1&m=Action_InventaireCharger', 
			{
				method: 'post', 
				parameters: 'id='+dg.id, 
				onComplete: charger_confirm,
				onFailure: fail
			});
}
function charger_confirm(originalRequest){
	
	var rval= originalRequest.responseText;
	var params=rval.split("|");
	
	$('request_msg').style.display="block";
	$('request_msgtxt').innerHTML=unescape(params[1]);
}
function submitMunForm(url, itemid, munid){
	showplzwait();
	
	var myAjax = new Ajax.Request(
			url,
			{
				method: 'post', 
				parameters: 'id='+itemid+'&munid='+munid, 
				onComplete: charger_confirmFin,
				onFailure: fail
			});
}
function charger_confirmFin(originalRequest){
	var rval= originalRequest.responseText;
	var params=rval.split("|");
	
	//0: Arme ID
	//1: Status
	//2: PA total
	//3: Inventaire PR
	//4: Arme Qte
	//5: Mun ID
	//6: Mun Qte
	if (params[1]=="OK"){
		$("qte_" + params[0]).innerHTML = params[4];
		$("qte_" + params[5]).innerHTML = params[6];
		window.parent.document.getElementById('perso_pa').innerHTML=params[2];
		window.parent.document.getElementById('perso_pr').innerHTML=params[3];
		hideplzwait();
	}else{
		$('request_msg').style.display="block";
		$('request_msgtxt').innerHTML=unescape(params[1]);
	}
}