var backendURL;
var applicationName;
var channel;
var category;
var accessToken;
var oldCount = 0;

var currentTitle = document.title;

function initialize() {
}

function startChat(url, appName, cat, chan, aToken) {
	backendURL = url;
	applicationName = appName;
	category = cat;
	channel = chan;
	accessToken = aToken;
	setInterval("reloadChat()", 2000);
}

function scroller(){
	if((size = $("#chatTextArea").height() - $("body").height()) > 0){
		window.scrollTo(0, size + 330);
	}
}

function reloadChat(){

	var count = 0;

	args = "code=1";
	args += "&application=" + applicationName;
	args += "&predicate=Category(" + category + ")Channel(" + channel + ")";
	args += "&accessToken=" + accessToken;

	var res = $.ajax({
		url : "backend/FindRequestHandler",
		dataType : 'json',
		data : args,
		async : false
	}).responseText;

	if((resJSON = $.parseJSON(res)) != null) {

		var quotesJSON = $.parseJSON(resJSON.data.results);

		if(quotesJSON != null) {
			var chat = new Array();
			$.each(quotesJSON, function(i, item) {
				quote = '<span Style="font-weight: bold; color: red; ">' + item.begin + '</span> :: ';
				quote += '<span Style="font-weight: bold; color: green; ">' + item.publisherName + '</span> : ';
				quote += '<span>' + item.data + '</span><br />';
				chat[count] = quote;
				count++;
			});
			chat.sort();

			if(count > oldCount){
				var chatContent = "";
				$.each(chat, function(i, item) {
					chatContent += item;
				});
				$('#chatTextAreaContent').html(chatContent);
				oldCount = count;
				newExcitingAlerts();
				scroller();
			}
		}
	} else {
//		alert("Cross Domain Error: ajax request from https://domain.com to http://domain.com");
	}
}

function newExcitingAlerts() {
	document.title = "New Message!";
	window.onmouseover = function() {
		document.title = currentTitle;
		window.onmousemove = null;
	};
}

function formatInputText(){
	$('#chatInsertTextFormated').val($('#chatInsertText').val().replace(/\n/g, '<br>'));
}

function submitNewQuote(formID){
	formatInputText();
	$('#chatInsertText').val("");
	publishDASPRequest(formID);
}

