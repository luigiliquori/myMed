
function array_diff (arr1) {
    // taken from php.js, and modified return type
    var retArr = [],
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
            retArr.push(arr1[k1]);
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

