function publishDASPRequest(formID){

	// store the current date if needed
	if($("#getDate") != null){
		$("#getDate").val(getFormatedDate);
	}

	$.ajax({
		type: 'POST',
		url : "#",
		data : $("#" + formID).serialize(),
		async : true
	});
}

