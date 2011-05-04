/**
 * @param src	url of javascript
 */
function include(/*string*/ src)
{
	var head	= document.getElementsByTagName("head")[0];
	var DSLScript  = document.createElement("script");
	DSLScript.src  = src;
	DSLScript.type = "text/javascript";
	head.appendChild(DSLScript);
	//head.removeChild(DSLScript);
}