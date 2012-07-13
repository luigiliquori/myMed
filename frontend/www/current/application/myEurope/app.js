
var isSub = 3;

var application="myEurope", predicate="";

/*$("#Search").live("pageshow", function() {
	var queryString = decodeURIComponent(location.search.substring(1));

	var params = queryString.split('&');
	
	var tags=[];
	for (i in params){
		if (params[i].split('=')[1] == "on"){
			tags.push(params[i].split('=')[0]);
		}	
	}
	tags.sort();
	
	isSub = 3;
	$.get('../../lib/dasp/ajax/Subscribe', { 
		code: 1, 
		application: application ,
		predicate: tags.join("") 
	}, function(data){
		var res = JSON.parse(data);
		if (res.sub)
			isSub = 0;
		$('#flip-a').val(isSub).slider('refresh');
		console.log("__ "+isSub+" "+tags);
	});
	//console.log("__ "+isSub+" "+tags);
});*/

/*$("#Home").live("pagecreate", function() {

});
*/



$('#tagSearch').live("keyup", function(event) {
	if (event.keyCode == 13) {
		$('#searchForm').submit();
	}
});


function showComment() {
	$('#CommentButton').fadeOut('fast');
    $('#Comments').fadeIn('slow');
    $('#Commenter').fadeIn('slow');
}

