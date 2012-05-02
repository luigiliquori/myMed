var step = 1;
var NB_STEP = 8;
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
	for(i=1; i<NB_STEP ; i++) {
		document.getElementById("step" + i).style.display = "none";
		document.getElementById("li" + i).style.display = "none";
	}
}

function changeStep(){
	cleanView();
	fadeIn("#step" + step);
	fadeIn("#li" + step);
}

function nextStep(){
	step = ++step%NB_STEP;
	if(step == 0){
		step = 1;
	}
	changeStep();
}


function back(){
	if(step != 1){
		step = --step%NB_STEP;
	}
	changeStep();
}

