function initialize() {
    geoloc();
}

function geoloc() {
	if (navigator.geolocation)
		var watchId = navigator.geolocation.watchPosition(successCallback, null, {enableHighAccuracy:true});
	else
	    alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
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

function changeDestination(dest){
	if (dest == "depart") {
		address = (document.getElementById("selectDepart").value + "").split("&&")[0];
		picture = (document.getElementById("selectDepart").value + "").split("&&")[1];
		document.getElementById("depart").value = address;
		document.getElementById("depart2").value = address;
		document.getElementById("dpicture").src = picture;
	} else {
		address = (document.getElementById("selectArrivee").value + "").split("&&")[0];
                picture = (document.getElementById("selectArrivee").value + "").split("&&")[1];
                document.getElementById("arrivee").value = address;
		document.getElementById("arrivee2").value = address;
                document.getElementById("apicture").src = picture;
	
	}
}
