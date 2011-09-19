var task = {};

function displayWindow(elementID){
		if(task[elementID] != 1) {
			jQuery.noConflict();  
			jQuery(document).ready(function(){
				jQuery(elementID).show("fast");
			});
			task[elementID] = 1;
		} else {
			hideWindow(elementID);
		}
}

function hideWindow(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).hide("normal");
	});
	task[elementID] = 0;
}

function fadeIn(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).fadeIn("fast");
	});
}

function fadeOut(elementID){
	jQuery.noConflict();  
	jQuery(document).ready(function(){
		jQuery(elementID).fadeOut("fast");
	});
}

function cleanView(){
	document.getElementById("adminUserRead").style.display = "none";
	document.getElementById("adminUserUpdate").style.display = "none";
	document.getElementById("adminUserDelete").style.display = "none";
	document.getElementById("adminApplicationRead").style.display = "none";
	document.getElementById("adminApplicationUpdate").style.display = "none";
	document.getElementById("adminApplicationDelete").style.display = "none";
}

