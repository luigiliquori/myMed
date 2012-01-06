function initDesktop(/*DivHTMLElement*/ othis)
{
	var columns	= othis.children;
	var glassPaneVisibility	= false;
	var glassPane	= null;
	(function /*_constructor_begin*/()
	{
		if(!othis.getElementsByClassName)
			othis.getElementsByClassName	= patchGEBCN.getElementsByClassName;
		var glassPaneList	= othis.getElementsByClassName("glassPane");
		columns	= othis.getElementsByClassName("column");
		glassPane	= glassPaneList[glassPaneList.length-1];
	})();
	othis.setGlassPaneVisibility	= function(/*boolean*/ visibility)
	{
		if(glassPaneVisibility != visibility)
		{
			if(visibility)
				glassPane.style.display	= "block";
			else
				glassPane.style.display	= "";
			glassPaneVisibility = visibility;
		}
	}
	othis.getColumnByPixelOffset = function(/*int*/ x)
	{
		var i = Math.floor((x*columns.length)/othis.clientWidth);
		if(i<0)			i=0;
		if(i>=columns.length)	i = columns.length-1;
		return columns[i];
	};
	(function /*_constructor_end*/()
	{
	})();
}
