/**
 *
 */
if(!getComputedStyle)
	var getComputedStyle = function(element, pseudoClass)
	{
		return element.currentStyle;
	}