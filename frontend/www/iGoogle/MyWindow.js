function newMyWindow(/*String*/ title/*=""*/, /*MyDesktop*/ desktop/*=new MyDesktop()*/, /*int*/ column/*=0*/, /*int*/ position /*=0*/)
{
	newMyWindow.basedWindow;
	var othis;
	var desktop		= desktop||new MyDesktop();
	var column		= column||0;
	var position	= position||0;
	var shadowDiv	= document.createElement("div");
	var minimize	= false;
	var maximize	= false;
    (function /*_constructor_begin*/()
    {
    	if(!newMyWindow.basedWindow)
    	{
			var xhr = createCrossXMLHttpRequest();
			xhr.open("GET", "window.xml", false);
			xhr.send(null);
			newMyWindow.basedWindow = xhr.responseXML.documentElement;
    	}
		othis = document.importNode(newMyWindow.basedWindow, true);
	})();
	/*int*/			othis.getColumn		= function()					{return column;};
	/*void*/		othis.setColumn		= function(/*int*/ val)			{column = val;};
	/*int*/			othis.getPosition	= function()					{return position;};
	/*void*/		othis.setPosition	= function(/*int*/ val)			{position = val;};
	/*String*/ othis.getTitle = function()
	{
		return othis.getElementsByClassName("title")[0].textContent;
	};
	othis.setTitle = function(/*String*/ val)
	{
		othis.getElementsByClassName("title")[0].textContent = val;
	};
	othis.setContent = function(/*HTMLElement|String*/ val)
	{
		if(val instanceof HTMLElement)
		{
			othis.getElementsByClassName("content")[0].innerHTML = "";
			othis.getElementsByClassName("content")[0].appendChild(val);
		}
		else
			othis.getElementsByClassName("content")[0].innerHTML = val;
	};
	othis.setDesktop = function(/*MyDEsktop*/ val)
	{
		desktop	= val;
	};
	/*MyDesktop*/ othis.getDesktop = function()
	{
		return desktop;
	};
	/*boolean*/ othis.getMinimize = function()
	{
		return minimize;
	};
	othis.setMinimize = function(/*boolean*/ val)
	{
		minimize	= val;
		if(val)
			othis.getElementsByClassName("content")[0].style.display	= "none";
		else
			othis.getElementsByClassName("content")[0].style.display	= "";
	}
	othis.toogleMinimize = function()
	{
		othis.setMinimize(!othis.getMinimize());
	}
	/*boolean*/ othis.getMaximize = function()
	{
		return maximize;
	};
	othis.setMaximize = function(/*boolean*/ val)
	{
		maximize	= val;
		if(val)
		{
			desktop.getHTMLElement().className	+= " childmaximized"
			othis.style.position	= "fixed";
			othis.style.width		= "100%";
			othis.style.height		= "100%";
			othis.style.top			= "0";
			othis.style.left		= "0";
			othis.style.margin		= "0";
			othis.style.zIndex		= "1";
			othis.style.display		= "block";
		}
		else
		{
			desktop.getHTMLElement().className	= desktop.getHTMLElement().className.replace(/ childmaximized/, "");
			othis.style.position	= "";
			othis.style.width		= "";
			othis.style.height		= "";
			othis.style.top			= "";
			othis.style.left		= "";
			othis.style.margin		= "";
			othis.style.zIndex		= "";
			othis.style.display		= "";
		}
	}
	othis.toogleMaximize = function()
	{
		othis.setMaximize(!othis.getMaximize());
	}
	othis.close = function()
	{
		othis.parentNode.removeChild(othis);
		delete othis;
	}
	// window drag and drop
	var mouseOffset = null;
	var startdrag = function(/*Event*/ evt)
	{
		var EVENT_MOUSE_LEFT = 0; // standard W3C (dont IE9)
		if(window.event&&(/MSIE (\d+)/.test(navigator.userAgent))&&(new Number(RegExp.$1)<9)) 	// standard IE (aussi accepté par IE9)
			var EVENT_MOUSE_LEFT = 1;
		if(evt.button == EVENT_MOUSE_LEFT && desktop)
		{
			mouseOffset = new Object();
			var wx = othis.offsetLeft;
			var wy = othis.offsetTop;
			mouseOffset.x	= evt.offsetX||evt.layerX;
			mouseOffset.y	= evt.offsetY||evt.layerY;
			shadowDiv.style.height	= othis.offsetHeight+"px";
			othis.parentNode.insertBefore(shadowDiv, othis);
			othis.style.width		= othis.clientWidth+"px";
			othis.style.position = "absolute";
			othis.style.left	= wx+"px";
			othis.style.top		= wy+"px";
			othis.style.zIndex	= "1";
			othis.style.margin	= "0";
			addEventListenerToElement(desktop.getHTMLElement(), "mousemove", drag);
			addEventListenerToElement(window, "mouseup", drop);
			evt.preventDefault();
		}
	};
	var getElementInColumn = function(/*HTMLElement*/ column, /*int*/ y)
	{
		var length = column.children.length;
		for(var i=0 ; i<length ; i++)
		{
			if(
					(column.children[i]!=othis)
					&&((y-column.children[i].offsetTop) < column.children[i].offsetHeight/2)
				)
			{
				return column.children[i];
			}
		}
		return null;
	};
	var drag = function(/*Event*/ evt)
	{
		var x	= (evt.offsetX||evt.layerX)+calcOffsetLeftFromDesktop(evt.target);
		var y	= (evt.offsetY||evt.layerY)+calcOffsetTopFromDesktop(evt.target);
		var column = desktop.getColumnByPixelOffset(x);
		var elem = getElementInColumn(column, y);
		if(elem)
			column.insertBefore(shadowDiv, elem)
		else
			column.appendChild(shadowDiv);
		othis.style.left	= (x-mouseOffset.x)+"px";
		othis.style.top		= (y-mouseOffset.y)+"px";
		evt.preventDefault();
	};
	var drop = function(/*Event*/ evt)
	{
		othis.style.width		= "";
		othis.style.position	= "";
		othis.style.zIndex		= "";
		othis.style.top			= "";
		othis.style.left		= "";
		othis.style.margin		= "";
		shadowDiv.parentNode.insertBefore(othis, shadowDiv);
		shadowDiv.parentNode.removeChild(shadowDiv);
		removeEventListenerToElement(desktop.getHTMLElement(), "mousemove", drag);
		removeEventListenerToElement(window, "mouseup", drop);
	};
	var calcOffsetTopFromDesktop = function(/*HTMLElement*/ element)
	{
		var offset = 0;
		for( ; element != desktop.getHTMLElement() ; element = element.parentNode)
			if(getComputedStyle(element, null).position != "static")
				offset += element.offsetTop;
		return offset
	};
	var calcOffsetLeftFromDesktop = function(/*HTMLElement*/ element)
	{
		var offset = 0;
		for( ; element != desktop.getHTMLElement() ; element = element.parentNode)
			if(getComputedStyle(element, null).position != "static")
				offset += element.offsetLeft;
		return offset
	};
	var stopPropagation = function(/*Event*/ evt)
	{
		evt.stopPropagation();
	};
    (function /*_constructor_end*/()
    {
		if(!othis.getElementsByClassName)
			othis.getElementsByClassName = patchGEBCN.getElementsByClassName;
		if(title)
			othis.setTitle(title);
		shadowDiv.className	= "windowshadow";
		addEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "mousedown", startdrag);
		addEventListenerToElement(othis.getElementsByClassName("minimize")[0], "click", othis.toogleMinimize);
		addEventListenerToElement(othis.getElementsByClassName("maximize")[0], "click", othis.toogleMaximize);
		addEventListenerToElement(othis.getElementsByClassName("close")[0], "click", othis.close);
		var buttons	= othis.getElementsByClassName("titlebar")[0].getElementsByTagName("button");
		for(var i=0 ; i<buttons.length ; i++)
			addEventListenerToElement(buttons[i], "mousedown", stopPropagation);
	})();
	return othis;
}
