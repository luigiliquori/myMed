
<<<<<<< HEAD
function hideLoadingBar() {
	$.mobile.hidePageLoadingMsg();
}
function showLoadingBar(text) {
	$.mobile.showPageLoadingMsg("d", text);

	setTimeout(hideLoadingBar, 3000); // make sure it stops one day
}
=======
function hideLoadingBar(){
	//hide loading status...
	loading = document.getElementById("loading");
	loading.style.display='none';
}

function showLoadingBar(text){
	//hide loading status...
	loading = document.getElementById("loading");
	if(text) {
		loading.innerHTML = "<center><span>" + text + "</span></center>";
	}
	loading.style.display = "block";
}

>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
