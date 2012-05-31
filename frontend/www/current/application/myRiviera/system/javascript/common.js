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

function hideLoadingBar(){
  //hide loading status...
  loading = document.getElementById("loading");
  loading.style.display='none';
}
function showLoadingBar(text){
	//hide loading status...
	$("#loading").html("<center><span>" + text + "</span></center>");
	$("#loading").fadeIn("fast", function() { setTimeout(hideLoadingBar, 3000);}); 
}

