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
	fadeOut("#home");
	document.getElementById("homeHeader").style.display = "none";
	
	fadeOut("#model");
	document.getElementById("modelHeader").style.display = "none";
	
	fadeOut("#architecture");
	document.getElementById("architectureHeader").style.display = "none";

	fadeOut("#vocabulary");
	document.getElementById("vocabularyHeader").style.display = "none";
	
	fadeOut("#workflow");
	document.getElementById("workflowHeader").style.display = "none";
	
	fadeOut("#rest");
	document.getElementById("restHeader").style.display = "none";
}

function changeView(elementID){
	cleanView();
	fadeIn("#" + elementID);
	fadeIn("#" + elementID + "Header");
}

function cleanMenu(){
	document.getElementById("designItems").style.display = "none";
	document.getElementById("softwareItems").style.display = "none";
	document.getElementById("prototypeItems").style.display = "none";
	document.getElementById("linksItems").style.display = "none";
}

function displayMenu(elementID){
	cleanMenu();
	fadeIn("#" + elementID + "Items");
}

function cleanArchiView(){
	document.getElementById("NetworkView").style.display = "none";
	document.getElementById("BackBoneView").style.display = "none";
	document.getElementById("NodeView").style.display = "none";
	document.getElementById("FrontEndView").style.display = "none";
	document.getElementById("BackEndView").style.display = "none";
	document.getElementById("VirtualMachineView").style.display = "none";
	document.getElementById("OperatingSystemView").style.display = "none";
	document.getElementById("KernelView").style.display = "none";
	document.getElementById("PhysicalMachineView").style.display = "none";	
}

function closeArchi(elementID){
	document.getElementById(elementID).style.display = "none";
	document.getElementById("menu").style.display = "block";	
	fadeIn("#View");
}

function displayArchi(elementID){
	// Clean View
	document.getElementById("View").style.display = "none";	
	document.getElementById("menu").style.display = "none";	
	// Display Archi
	fadeIn("#" + elementID);
}

function changeArchiView(elementID){
	cleanArchiView();
	// Display ArchiView
	document.getElementById(elementID + "View").style.display = "block";
}