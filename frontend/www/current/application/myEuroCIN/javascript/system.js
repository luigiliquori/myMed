
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