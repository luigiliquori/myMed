function geoloc(){
	if (navigator.geolocation)
		var watchId = navigator.geolocation.watchPosition(successCallback, null, {enableHighAccuracy:true});
	else
	    alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
}
	 
function successCallback(position){
	
	// store the position with an AJAX request
	 var xhr; 
	    try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	    catch (e) 
	    {
	        try {   xhr = new ActiveXObject('Microsoft.XMLHTTP'); }
	        catch (e2) 
	        {
	           try {  xhr = new XMLHttpRequest();  }
	           catch (e3) {  xhr = false;   }
	         }
	    }
	 
	    xhr.onreadystatechange  = function() 
	    { 
	       if(xhr.readyState  == 4)
	       {
	        if(xhr.status  == 200) 
	            alert("Position enregistrée."); 
	        else 
	        	alert("Error code " + xhr.status);
	        }
	    }; 

	   xhr.open( "GET", "index.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,  true); 
	   xhr.send(null); 
	
	  map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
	  var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
	    map: map
	  });
}

