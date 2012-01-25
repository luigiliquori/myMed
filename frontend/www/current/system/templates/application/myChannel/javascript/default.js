var backendURL;
var applicationName;
var channel;
var category;
var accessToken;
var oldCount = 0;
var reloadID; 

function initialize() {
}

function startChat(url, appName, cat, chan, aToken) {
	backendURL = url;
	applicationName = appName;
	category = cat;
	channel = chan;
	accessToken = aToken;
	reloadID = setTimeout("reloadChat()", 2000);
}

function stopChat(){
	clearInterval(reloadID);
}

function scroller(){
	window.scrollTo(0, $("#chatTextArea").height());
}

function reloadChat(){
	
	var chatContent = "";
	var count = 0;
	
	args = "code=1";
	args += "&application=" + applicationName;
	args += "&predicate=Category(" + category + ")Channel(" + channel + ")";
	args += "&accessToken=" + accessToken;
	
	var quotes = $.ajax({
		url : backendURL + "/FindRequestHandler",
		dataType : 'json',
		data : args,
		async : false
	}).responseText;
	
	var quotesJSON = $.parseJSON($.parseJSON(quotes).data.results);
	
	if(quotesJSON != null) {
		$.each(quotesJSON, function(i, item) {
			chatContent += '<span Style="font-weight: bold; color: red; ">' + item.begin + '</span> :: ';
			chatContent += '<span Style="font-weight: bold; color: green; ">' + item.publisherName + '</span> : ';
			chatContent += '<span>' + item.data + '</span><br />';
			count++;
		});
	}
	
	if(count > oldCount){
		$('#chatTextAreaContent').html(chatContent);
		oldCount = count;
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


function publishDASPRequest(formID){
	var reponse = $.ajax({
		type: 'POST',
		url :"index.php",
		data : $("#" + formID)	.serialize(),
		async : false
	}).responseText;
	alert(reponse);
}
