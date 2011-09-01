function MobileFixedElements()
{
	var fixedElementsList	= new Array();
	var mobileDesign	= false;
	this.addElement	= function(/*HTMLElement*/ element)
	{
		fixedElementsList.push(element);
	};
	var updateScroll	= function()
	{
		var translate	= "translateY(" + window.scrollY + "px)";
		for(var i=0 ; i<fixedElementsList.length ; i++)
		{
			fixedElementsList[i].style.webkitTransform	= translate;
			fixedElementsList[i].style.OTransform		= translate;
			fixedElementsList[i].style.MozTransform		= translate;
			fixedElementsList[i].style.transform		= translate;
		}
	};
	var addEvent	= function()
	{
		window.addEventListener("scroll", updateScroll, false);
		document.addEventListener("scroll", updateScroll, false);
	};
	var removeEvent	= function()
	{
		window.removeEventListener("scroll", updateScroll, false);
		document.removeEventListener("scroll", updateScroll, false);
		for(var i=0 ; i<fixedElementsList.length ; i++)
		{
			fixedElementsList[i].style.webkitTransform	= "";
			fixedElementsList[i].style.OTransform		= "";
			fixedElementsList[i].style.MozTransform		= "";
			fixedElementsList[i].style.transform		= "";
		}
	};
	var updateMode		= function()
	{
		if(window.isMobileDesign() !== mobileDesign)
		{
			if(window.isMobileDesign())
				addEvent();
			else
				removeEvent();
			mobileDesign	= window.isMobileDesign();
		}
	};
	if(window.addEventListener)// no old IE
	{
		window.addEventListener("resize", updateMode, false);
		updateMode();
	}
}
window.mobileFixedElements = new MobileFixedElements();
