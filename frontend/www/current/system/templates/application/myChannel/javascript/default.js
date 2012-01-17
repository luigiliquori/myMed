function initialize() {
	setTimeout("scroller()", 500);
	setInterval("reloadChat()", 20000);
}

function scroller(){
	window.scrollTo(0, $("#chatTextArea").height());
}

function reloadChat(){
	if(document.getElementById("quote").value == ""){
		location.reload();
	}
}
