var cameraState = 0;

function initialize() {
	// INIT JAVASCRIPT FOR THE TEMPLATE
}


function enableCamera() {
	if(cameraState == 0) {
		$("#picture").attr("src", "img/camera_active.png");
		$("#pictureValue").attr("value", "1");
		cameraState = 1;
	} else {
		$("#picture").attr("src", "img/camera.png");
		$("#pictureValue").attr("value", "0");
		cameraState = 0;
	}
}