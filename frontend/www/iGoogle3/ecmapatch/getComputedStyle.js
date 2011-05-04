/**
 * 
 */
addEventListenerToElement(window, "load", function()
{
	if(!window.getComputedStyle)
		window.getComputedStyle = function(element, pseudoClass)
		{
			return element.currentStyle;
		}
});