
function hideLoadingBar() {
	$.mobile.hidePageLoadingMsg();
}
function showLoadingBar(text) {
	$.mobile.showPageLoadingMsg("d", text);

	setTimeout(hideLoadingBar, 3000); // make sure it stops one day
}