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
