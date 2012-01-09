var keySize = 0;
var valueSize = 0;


/* --------------------------------------------------------- */
/*                  Put/Remove attributes                    */
/* --------------------------------------------------------- */
function addKeyOrValue(keyOrValue){
	if(keyOrValue == "key"){
		size = keySize++;
	} else {
		size = valueSize++;
	}
	if(size == 0 ){
		fadeIn("#clear" + keyOrValue);
	}
	label = document.getElementById(keyOrValue + "Label").value;
	select = document.getElementById(keyOrValue + "Type");
	type = select.options[select.selectedIndex].value ;
	description = document.getElementById(keyOrValue + "Description").value;
	document.getElementById(keyOrValue + "resumer").innerHTML += 
		"<li id='li" + keyOrValue + size + "'>" + label + ":" + type + 
		"<input type='hidden' name='" + keyOrValue + size + "name' value='"+ label + "' />" +
		"<input type='hidden' name='" + keyOrValue + size + "type' value='"+ type + "' />" +
		"<input type='hidden' name='" + keyOrValue + size + "description' value='"+ description + "' />" +
		"</li>";
	document.getElementById(keyOrValue + "Number").value = size+1;
}

function clearKeyOrValue(keyOrValue){
	if(keyOrValue == "key"){
		size = keySize;
		keySize = 0;
	} else {
		size = valueSize;
		valueSize = 0;
	}
	for(i = 0 ; i < size ; i++){
		jQuery.noConflict();
		jQuery("#li" + keyOrValue + i).remove();
	}
	fadeOut("#clear" + keyOrValue);
}
