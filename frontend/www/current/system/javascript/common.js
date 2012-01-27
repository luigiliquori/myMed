function displayFrame(frame){
	$('#' + frame).show("slow");
}

function hideFrame(frame){
	// TODO use jQuery to hide the frame
	$('#' + frame, top.document).hide("slow");
}

function getFormatedDate(){
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	var second = currentTime.getSeconds();

	if (day < 10){
		day = "0" + day;
	}
	if (month < 10){
		month = "0" + month;
	}
	if (second < 10){
		second = "0" + second;
	}
	if (minutes < 10){
		minutes = "0" + minutes;
	}
	if(hours > 11){
		second += " pm";
	} else {
		second += " am";
	}
	
	return day + "/" + month + "/" + year + ", " + hours + ":" + minutes + ":" + second;
}

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

/**
 * Automatically sets the date input field
 */
$(function() {
	// Handler for .ready() called.
	var d=new Date();
	
	$("#date").val(("0" + (d.getHours())).slice(-2)+":"+("0" + (d.getMinutes())).slice(-2)+" le "+("0" + (d.getDate())).slice(-2)+'/'+("0" + (d.getMonth() + 1)).slice(-2)+'/'+d.getFullYear());
});