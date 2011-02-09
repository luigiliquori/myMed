function submitForm(request) {
	if (request == "search") {
		request = "http://mymed2.sophia.inria.fr:8080/services/RequestHandler?act=5&key2="
			+ document.getElementById('from1').value
			+ document.getElementById('to1').value
			+ document.getElementById('when11').value
			+ document.getElementById('when12').value
			+ document.getElementById('when13').value;
	} else if (request == "publish") {
		request = "http://mymed2.sophia.inria.fr:8080/services/RequestHandler?act=4&key1="
			+ document.getElementById('from2').value
			+ document.getElementById('to2').value
			+ document.getElementById('when21').value
			+ document.getElementById('when22').value
			+ document.getElementById('when23').value
			+ "&value1="
			+ document.getElementById('userId').innerHTML;
	}

	// AJAX REQUESTs
	jQuery.noConflict();
	alert(request);
	bodyContent = jQuery.get("./phpProxy.php", {
		url : "http://mymed2.sophia.inria.fr:8080/services/RequestHandler",
		request : request
		
	});
	alert(bodyContent.responseText);
	
	// FEEDBACK
	if (request == "publish"){
		alert("trip published!");
	}
}
