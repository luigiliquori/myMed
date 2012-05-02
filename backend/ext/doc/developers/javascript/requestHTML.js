function submitForm(request) {
	if (request == "publish") {
		request1 = "http://mymed2.sophia.inria.fr:8084/mymed/MyMedTestClass?act=4&key1="
			+ document.getElementById('from2').value
			+ document.getElementById('to2').value
			+ document.getElementById('when21').value
			+ document.getElementById('when22').value
			+ document.getElementById('when23').value
			+ "&value1="
			+ document.getElementById('userId').innerHTML;
		request2 = "http://mymed2.sophia.inria.fr:8084/mymed/MyMedTestClass?act=4&key1="
			+ document.getElementById('from2').value
			+ document.getElementById('to2').value
			+ document.getElementById('when21').value
			+ document.getElementById('when22').value
			+ document.getElementById('when23').value
			+ document.getElementById('userId').innerHTML
			+ "&value1="
			+ document.getElementById('description').value;
	}

	// AJAX REQUESTs
	jQuery.noConflict();  
	jQuery.ajax({
		  url: request1,
		  async: false
		});
	jQuery.noConflict();  
	jQuery.ajax({
		  url: request2,
		  async: false
		});
	alert("trip published!");
}
