var step = 1;
var labelNumber = 1;
var valueNumber = 1;
var appCurrent = "null";

function displayWindow(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).show("normal");
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

function cleanView(){
	document.getElementById("tab1").style.display = "none";
	document.getElementById("tab2").style.display = "none";
	document.getElementById("tab3").style.display = "none";
}

