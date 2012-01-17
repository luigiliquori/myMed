function displayFrame(frame){
	$('#' + frame).show("slow");
}

function hideFrame(frame){
	// TODO use jQuery to hide the frame
		$('#' + frame, top.document).hide("slow");
}

// touchOverflow -- Only for iOS5
$(document).bind("mobileinit", function(){
  $.mobile.touchOverflowEnabled = true;
});

function newExcitingAlerts() {
    var oldTitle = document.title;
    var msg = "New Message!";
    var timeoutId = setInterval(function() {
        document.title = document.title == msg ? ' ' : msg;
    }, 1000);
    $("body").onmouseover = function() {
        clearInterval(timeoutId);
        document.title = oldTitle;
        window.onmousemove = null;
    };
}

