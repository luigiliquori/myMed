var reload = true;

function initialize() {
	setTimeout("scroller()", 500);
	setTimeout("reloadChat()", 1000);
}

function scroller(){
	window.scrollTo(0, $("#chatTextArea").height());
}

function reloadChat(){
	if(document.getElementById("quote").value == "" && reload){
		location.reload();
	}
}

function newExcitingAlerts() {
	var oldTitle = document.title;
    var msg = "New Message!";
    reload = false;
    var timeoutId = setInterval(function() {
        document.title = document.title == msg ? ' ' : msg;
    }, 1000);
    window.onmouseover = function() {
        clearInterval(timeoutId);
        document.title = oldTitle;
        window.onmousemove = null;
        reload = true;
        reloadChat();
    };
}