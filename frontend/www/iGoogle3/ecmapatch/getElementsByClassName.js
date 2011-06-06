var patchGEBCN = 
{
	getElementsByTagName : function(incorrectUse)
	{
		alert("Incorrect use of patchGEBCN\n"
			+ "You need to call it on an HTML tag\n"
			+ "\n"
			+ "For install you can use patchGEBCN.initDocument() or patchGEBCN.initAll() functions\n"
			+ "or affect to the element\n"
			+ "exemple :\"element.getElementsByClassName = patchGEBCN.getElementsByClassName;\"");
		return new Array(0);
	},
	getElementsByClassName : function(className)
	{
		var allTags = this.getElementsByTagName("*");
		var returnedArray = new Array();
		var index = 0;
		for(var i=0 ; i<allTags.length ; i++)
		{
			var classAttribute	= allTags[i].getAttribute("class");
			if( classAttribute&&(classAttribute.search(className) != -1) )
			{
				returnedArray[index] = allTags[i];
				index++;
			}
		}
		return returnedArray;
	},
	initDocument : function()
	{
		if(!document.getElementsByClassName)
			document.getElementsByClassName = getElementsByClassName;
	},
	initAll : function()
	{
		document.getElementsByClassName = getElementsByClassName;
		var allTags = document.getElementsByTagName("*");
		for(var i=0 ; i<allTags.length ; i++)
		{
			if(!allTags[i].getElementsByClassName)
				allTags[i].getElementsByClassName = getElementsByClassName;
		}
	}
}