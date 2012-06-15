
function array_diff (arr1) {
    // Returns the entries of arr1 that have values which are not present in any of the others arguments.  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/array_diff
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Sanjoy Roy
    // +    revised by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: array_diff(['Kevin', 'van', 'Zonneveld'], ['van', 'Zonneveld']);
    // *     returns 1: {0:'Kevin'}
    var retArr = {},
        argl = arguments.length,
        k1 = '',
        i = 1,
        k = '',
        arr = {};
 
    arr1keys: for (k1 in arr1) {
        for (i = 1; i < argl; i++) {
            arr = arguments[i];
            for (k in arr) {
                if (arr[k] === arr1[k1]) {
                    // If it reaches here, it was found in at least one array, so try next value
                    continue arr1keys;
                }
            }
            retArr[k1] = arr1[k1];
        }
    }
 
    return retArr;
}

function displayFrame(frame){
	$('#' + frame).show("slow");
}

function hideFrame(frame){
	// TODO use jQuery to hide the frame
	$('#' + frame, top.document).hide("slow");
}

function getFormatedDate(){
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	var second = currentTime.getSeconds();

	if (day < 10){
		day = "0" + day;
	}
	if (month < 10){
		month = "0" + month;
	}
	if (second < 10){
		second = "0" + second;
	}
	if (minutes < 10){
		minutes = "0" + minutes;
	}
	if(hours > 11){
		second += " pm";
	} else {
		second += " am";
	}
	
	return day + "/" + month + "/" + year + ", " + hours + ":" + minutes + ":" + second;
}

function hideLoadingBar() {
	$.mobile.hidePageLoadingMsg();
}
function showLoadingBar(text) {
	$.mobile.showPageLoadingMsg("d", text);

	setTimeout(hideLoadingBar, 3000); // make sure it stops one day
}

