function initialize() {
	geoloc();
}

function geoloc() {
	if (navigator.geolocation) {
		navigator.geolocation.watchPosition(successCallback, null, {enableHighAccuracy:true});
	} else {
	    alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
	}
}

function successCallback(position){
	// store the position with an AJAX request
	 var xhr; 
	    try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	    catch (e) {
	        try {   xhr = new ActiveXObject('Microsoft.XMLHTTP'); }
	        catch (e2) {
	           try {  xhr = new XMLHttpRequest();  }
	           catch (e3) {  xhr = false;   }
	         }
	    }
	 
	    xhr.onreadystatechange  = function() { 
	       if(xhr.readyState  == 4) {
	       		if(xhr.status  != 200) { 
	        		alert("Error code " + xhr.status);
	       		}
	        }
	    }; 

	   xhr.open( "GET", "index.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,  true); 
	   xhr.send(null); 
}

// COMMON DISPLAY/HIDE FUNCTIONS
function displaySection(elt){
	$(elt).show();
}

function hideSection(elt){
	$(elt).hide();
}

function fadeInSection(elt){
	$(elt).fadeIn("slow");
}

function fadeOutSection(elt){
	$(elt).fadeIn("slow");
}


function hideOverflow(elt) {
	$('#' + elt).css("overflow", "hidden");
	hideSection('#' + elt + "HideButton");
	fadeInSection('#' + elt + "ShowButton");
}

function displayOverflow(elt) {
	$('#' + elt).css("overflow", "visible");
	hideSection('#' + elt + "ShowButton");
	fadeInSection('#' + elt + "HideButton");
}

