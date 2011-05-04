/**
 * @function createCrossXMLHttpRequest
 * permet d'avoir une classe XMLHttpRequest construite suivant l'implémentation
 */
var createCrossXMLHttpRequest = function()
{
	if (window.ActiveXObject)
	{
		try
		{
			return new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	else if (window.XMLHttpRequest)
		return new XMLHttpRequest();
	else
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
	return null;
}