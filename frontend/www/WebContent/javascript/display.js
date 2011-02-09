var current = "";

function displayWindow(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).show("fast");
	});
}

function hideWindow(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).hide("normal");
	});
}

function fadeIn(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).fadeIn("slow");
	});
}

function fadeOut(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).fadeOut("slow");
	});
}

function activeDock(elementID){
	document.getElementById(elementID).style.display = "none";
//	displayWindow("#" + elementID + "H");
	document.getElementById(elementID + "H").style.display = "block";
}

function desactiveDock(elementID){
	document.getElementById(elementID).style.display = "block";
//	displayWindow("#" + elementID + "H");
	document.getElementById(elementID + "H").style.display = "none";
}

function launchApplication(elementID, isMapApplication){
	window.setTimeout("cleanView()", 1000);
	fadeIn("#" + elementID + "Splash");
	window.setTimeout("fadeOut('#" + elementID + "Splash')", 5000);
	window.setTimeout("fadeIn('#" + elementID + "')",  5000);
	if(isMapApplication){
//		window.setTimeout("fadeIn('#map_canvas')",  5000);
	}
}

function cleanView(){
	//cleanMenu();
	document.getElementById("app1").style.display = "block";
	document.getElementById("app1H").style.display = "none";
	document.getElementById("app3").style.display = "block";
	document.getElementById("app3H").style.display = "none";
	document.getElementById("app7").style.display = "block";
	document.getElementById("app7H").style.display = "none";
	document.getElementById("warning").style.display = "none";
	
}

function cleanMenu(){
	document.getElementById("fichier").style.display = "none";
	document.getElementById("edition").style.display = "none";
	document.getElementById("aide").style.display = "none";
}

function changeMenu(elementID){
	cleanMenu();
	displayWindow("#" + elementID);
}

function showLoginView(){
	document.getElementById("connexion").style.display = "none";
	fadeIn("#login");
}

function hideLoginView(){
	document.getElementById("login").style.display = "none";
	fadeIn("#connexion");
}
