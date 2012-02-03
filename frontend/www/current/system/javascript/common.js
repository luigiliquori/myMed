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
 * Automatically refresh date
 */
$(function() {
	
	//$(".ui-input-datebox").css("display","none");

	var d=new Date();
	$("#date").find(".ui-btn-text").html( d.getHours()+":"+("0" + (d.getMinutes())).slice(-2)+" le "+ d.getDate()+'/'+d.getMonth() +'/'+d.getFullYear());
		
	$("#depart").css("width","70%");
	$("#arrivee").css("width", "70%");
	$(".ui-select").css("width","80%");
	
	$("#selectarrivee").parent().css({
		"margin-top": "-48px",
		"margin-left": "150px"
	});	

	
	/*var auto_refresh = setInterval(
		function(){
			var tmp=new Date();
			if (tmp.getMinutes() != d.getMinutes()){
				d=tmp;
				$('#date').fadeOut('slow').val( d.getHours()+":"+("0" + (d.getMinutes())).slice(-2)+" "+ d.getDate()+'/'+d.getMonth() +'/'+d.getFullYear()).fadeIn("slow");
			}
		}, 10000
	);*/
	
});




