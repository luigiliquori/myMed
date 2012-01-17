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

function newExcitingAlerts() {
	var oldTitle = document.title;
    var msg = "New Message!";
    var timeoutId = setInterval(function() {
        document.title = document.title == msg ? ' ' : msg;
    }, 1000);
    window.onmouseover = function() {
        clearInterval(timeoutId);
        document.title = oldTitle;
        window.onmousemove = null;
    };
}