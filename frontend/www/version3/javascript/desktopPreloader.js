function desktopPreloader(/*HTMLElement*/ desktopContainer, /*HTMLElement*/ tabList, /*function*/ onLoadingEnd)
{
	var tabItemList	= tabList.getElementsByTagName('li');
	var importDiv	= document.createElement("div");
	var desktopNbBefore	= 0;
	var desktopLoading	= 0;
	function focusDesktop(/*boolean*/ dontchangeurl/*=false*/)
	{
		desktopContainer.childNodes
		for(var i=0 ; i<desktopContainer.childNodes.length ; i++)
			desktopContainer.childNodes[i].className	= desktopContainer.childNodes[i].className.replace(/\s?focus/g, "");
		for(var i=0 ; i<tabItemList.length ; i++)
			tabItemList[i].className	= tabItemList[i].className.replace(/\s?focus/g, "");
		this.className	+= " focus";
		tabItemList[this.desktopId].className	+= " focus";
		desktopContainer.style.left	= (this.desktopId*-100)+"%";
		if(!tabList.getElementsByClassName)
			tabList.getElementsByClassName	= patchGEBCN.getElementsByClassName;
		var mobileSliderList	= tabList.getElementsByClassName("mobile_slider")
		mobileSliderList[mobileSliderList.length-1].style.left	= (this.desktopId*100/desktopContainer.childNodes.length)+"%";
		
		if(!dontchangeurl)
		{
			var tabName	= this.id.replace(/^desktop/, "");
			try
			{
				history.pushState(this.id, tabName, "?tab="+tabName);
			}
			catch(ex)
			{
				if(console)
					console.warn(ex);
			}
		}
	}
	function initTabs()
	{
		for(var i=0 ; i<tabItemList.length ; i++)
			tabItemList[i].onclick = function(){document.getElementById("desktop"+this.getElementsByTagName("a")[0].search.replace(/^\?tab=/, "")).focus();return false;};
	}
	function extractUrlParams()
	{
		var params	= new Object();
		try
		{
			var tab	= location.search.substring(1).split('&');
			for(var i=0 ; i<tab.length ; i++)
			{
				var tmp = tab[i].split('=');
				params[decodeURIComponent(tmp[0])]=decodeURIComponent(tmp[1]);
			}
		}
		catch(ex)
		{
			if(console&&console.info)
				console.info(ex);
		}
		return params;
	}
	function insertDesktop(/*HTMLElement*/ element, /*int*/ position)
	{
		element.className = element.className.replace(/ ?focus/, "");
		var desktopList	= desktopContainer.childNodes;
		desktopNbBefore++;
		for(var i=0 ; i<desktopList.length ; i++)
		{
			if(desktopList[i].desktopId > position)
			{
				desktopContainer.insertBefore(element, desktopList[i]);
				break;
			}
			else if(desktopList[i].className.search(/(^|\s)focus(\s|$)/) > -1)
				desktopNbBefore--;
		}
		if(i==desktopList.length)
			desktopContainer.appendChild(element);
		else
			desktopContainer.style.left	= (desktopNbBefore*-100)+"%";
		element.desktopId	= position;
		element.focus	= focusDesktop;
		desktopLoading--;
		
		// init desktop and windows (iFrame only)
		if(!isMaxWidthMobileDesign())
		{
			initDesktop(element);
			if(!element.getElementsByClassName)
				element.getElementsByClassName	= patchGEBCN.getElementsByClassName;
			var windowsList	= element.getElementsByClassName("window");
			for(var i=0 ; i<windowsList.length ; i++)
				initWindow(windowsList[i]);
		}
		
		if(desktopLoading == 0)
		{
			initTabs()
			addEventListenerToElement(window, "popstate", function(event){
				var param	= extractUrlParams();
				if(param["tab"])
					document.getElementById("desktop"+param["tab"]).focus(true);
				else
					desktopContainer.children[0].focus(true);
			})
			if(onLoadingEnd)
				onLoadingEnd();
		}
	}
	function load(/*String*/ url, /*int*/ position)
	{
		var xhr	= createCrossXMLHttpRequest();
		xhr.open("GET", url+"&template=fraghtml&application.fraghtml", true);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				importDiv.innerHTML = xhr.responseText;
				insertDesktop(importDiv.childNodes[0].getElementsByTagName("div")[0], position);
			}
		};
		desktopLoading++;
		xhr.send(null);
	}
	for(var i=0 ; i<desktopContainer.childNodes.length ; i++)
		if(desktopContainer.childNodes[i].nodeName == "#text")
		{
			desktopContainer.removeChild(desktopContainer.childNodes[i]);
			i--;
		}
	for(var i=0 ; i<tabItemList.length ; i++)
	{
		if(tabItemList[i].className.search(/(^| )focus( |$)/) == -1)
			load(tabItemList[i].getElementsByTagName("a")[0].href, i);
		else
		{
			var focusElem	= document.getElementById("desktop"+tabItemList[i].getElementsByTagName("a")[0].search.replace(/^\?tab=/, ""))
			focusElem.focus		= focusDesktop;
			focusElem.desktopId	= i;
		}
	}
}
