function initWindow(/*DivHTMLElement*/ othis)
{
	var app	= othis.id.replace(/^window_/,"");
	othis.innerHTML	= '\n'+
		'<div class="titlebar">\n'+
		'	<div class="title"><a href="application/'+app+'"><img src="'+ROOTPATH+'services/'+app+'/icon.png" alt="" />'+app+'</a></div>\n'+
		'	<div class="titleButtons"><!--\n'+
		'		--><button class="options" onclick="this.focus();"><span>options</span></button><!--\n'+
		'		--><div class="menu">\n'+
		'			<ul>\n'+
		'				<li><button>Partager ce service</button></li>\n'+
		'				<li><button>Signaler ce service</button></li>\n'+
		'				<li><hr /></li>\n'+
		'				<li><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.toogleMinimize()">Réduire ce service</button></li>\n'+
		'				<li><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.toogleMaximize()">Agrandir ce service</button></li>\n'+
		'				<li><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.close()">Supprimer ce service</button></li>\n'+
		'			</ul>\n'+
		'		</div><!--\n'+
		'		--><button class="minimize" title="minimize" onclick="this.parentNode.parentNode.parentNode.toogleMinimize()"><span>-</span></button><!--\n'+
		'		--><button class="maximize" title="maximize" onclick="this.parentNode.parentNode.parentNode.toogleMaximize()"><span>+</span></button><!--\n'+
		'		--><button class="close" title="delete" onclick="this.parentNode.parentNode.parentNode.close()"><span>x</span></button><!--\n'+
		'	--></div>\n'+
		'</div>\n'+
		'<div class="content">\n'+
		'	<!--[if IE 8]>\n'+
		'	<iframe allowtransparency="true" frameBorder="0" src="application/'+app+'?template=onlycontent" id="'+app+'">\n'+
		'		<a href="application/'+app+'">Démarrer l\'application</a>\n'+
		'	</iframe>\n'+
		'	<![endif]-->\n'+
		'	<!--[if gt IE 8]><!-->\n'+
		'	<iframe src="application/'+app+'?template=onlycontent" id="'+app+'">\n'+
		'		<a href="application/'+app+'">Démarrer l\'application</a>\n'+
		'	</iframe>\n'+
		'	<!--><![endif]-->\n'+
		'</div>\n'+
		'<div class="rightResizer"></div>\n'+
		'<div class="leftResizer"></div>\n'+
		'<div class="bottomResizer"></div>';
	var desktop			= othis.parentNode.parentNode;
	var shadowDiv		= document.createElement("div");
	var bottomResizer	= othis.getElementsByClassName("bottomResizer");bottomResizer = bottomResizer[bottomResizer.length-1];
	var contentElement	= othis.getElementsByClassName("content")[0];
	var height			= othis.clientHeight;
	var minimize		= false;
	var maximize		= false;
	/*boolean*/ othis.getMinimize = function()
	{
		return minimize;
	};
	othis.setMinimize = function(/*boolean*/ val)
	{
		minimize	= val;
		if(val)
		{
			othis.getElementsByClassName("content")[0].style.display	= "none";
			othis.style.height	= "auto";
			othis.style.minHeight	= "0";
		}
		else
		{
			othis.getElementsByClassName("content")[0].style.display	= "";
			othis.style.height	= height+"px";
			othis.style.minHeight	= "";
		}
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
		if(maximize != val)
		{
			maximize	= val;
			if(val)
			{
				var padding		= new Object();
				desktop.className	+= " childmaximized"
				othis.className			+= " maximized";
				othis.style.height	= "";
			}
			else
			{
				desktop.className	= desktop.className.replace(/ childmaximized/, "");
				othis.className			= othis.className.replace(/ maximized/g, "");
				othis.style.height	= height+"px";
			}
			var resizeEvt = document.createEvent("Event");
			resizeEvt.initEvent("resize", true, false);
			othis.dispatchEvent(resizeEvt);
		}
	}
	othis.toogleMaximize = function()
	{
		othis.setMaximize(!othis.getMaximize());
	}
	othis.close = function()
	{
		othis.setMaximize(false);
		othis.parentNode.removeChild(othis);
		othis = null;
	}
	var isIELessThan	= function(/*double*/ requireVersion)
	{
		if(typeof(window.ieVersion) != "undefined")
			if(window.ieVersion < requireVersion)
				return true;
		return false;
	}
	// window drag and drop
	var mouseOffset = null;
	var titleMouseDown = function(/*MouseEvent*/ evt)
	{
		var EVENT_MOUSE_LEFT = 0; // standard W3C (dont IE9)
		if(window.event&&isIELessThan(9)) 	// standard IE (aussi accepté par IE9)
			var EVENT_MOUSE_LEFT = 1;
		if(evt.button == EVENT_MOUSE_LEFT && !window.isMobileDesign())
		{
			addEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "mousemove", startdrag);
			addEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "mouseup", function(){
				removeEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "mousemove", startdrag);
			});
			evt.preventDefault();
		}
	}
	var startdrag = function(/*MouseEvent*/ evt)
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
		othis.getElementsByTagName('iframe')[0].style.visibility	= "hidden";
		desktop.setGlassPaneVisibility(true);
		addEventListenerToElement(desktop, "mousemove", drag);
		if(isIELessThan(9))
			addEventListenerToElement(document, "mouseup", drop);
		else
			addEventListenerToElement(window, "mouseup", drop);
		removeEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "mousemove", startdrag);
		evt.preventDefault();
	};
	var getElementInColumn = function(/*HTMLElement*/ column, /*int*/ y)
	{
		var length = column.childNodes.length;
		for(var i=0 ; i<length ; i++)
		{
			var element = column.childNodes[i];
			if(element.nodeType	== element.ELEMENT_NODE)
			{
				if(
						(element!=othis)
						&&((y-element.offsetTop) < element.offsetHeight/2)
					)
				{
					return element;
				}
			}
		}
		return null;
	};
	var drag = function(/*MouseEvent*/ evt)
	{
		var x	= (evt.layerX||evt.offsetX)+calcOffsetLeftFromDesktop(evt.target);
		var y	= (evt.layerY||evt.offsetY)+calcOffsetTopFromDesktop(evt.target);//*
		var column = desktop.getColumnByPixelOffset(x);
		var elem = getElementInColumn(column, y);
		if(elem)
			column.insertBefore(shadowDiv, elem)
		else
			column.appendChild(shadowDiv);
		othis.style.left	= (x-mouseOffset.x)+"px";
		othis.style.top		= (y-mouseOffset.y)+"px";//*/
		evt.preventDefault();
	};
	var drop = function(/*MouseEvent*/ evt)
	{
		othis.style.width		= "";
		othis.style.position	= "";
		othis.style.zIndex		= "";
		othis.style.top			= "";
		othis.style.left		= "";
		othis.style.margin		= "";
		othis.getElementsByTagName('iframe')[0].style.visibility	= "";
		desktop.setGlassPaneVisibility(false);
		shadowDiv.parentNode.insertBefore(othis, shadowDiv);
		shadowDiv.parentNode.removeChild(shadowDiv);
		removeEventListenerToElement(desktop, "mousemove", drag);
		if(isIELessThan(9))
			removeEventListenerToElement(document, "mouseup", drop);
		else
			removeEventListenerToElement(window, "mouseup", drop);
	};
	var calcOffsetTopFromDesktop = function(/*HTMLElement*/ element)
	{
		var offset = 0;
		for( ; element != desktop ; element = element.parentNode)
			if(getComputedStyle(element, null).position != "static")
				offset += element.offsetTop;
		return offset
	};
	var calcOffsetLeftFromDesktop = function(/*HTMLElement*/ element)
	{
		var offset = 0;
		for( ; element != desktop ; element = element.parentNode)
			if(getComputedStyle(element, null).position != "static")
				offset += element.offsetLeft;
		return offset
	};
	var startVerticalResize = function(/*MouseEvent*/ evt)
	{
		var EVENT_MOUSE_LEFT = 0; // standard W3C (dont IE9)
		if(window.event&&isIELessThan(9)) 	// standard IE (aussi accepté par IE9)
			var EVENT_MOUSE_LEFT = 1;
		if(evt.button == EVENT_MOUSE_LEFT)
		{
			mouseOffset = new Object();
			mouseOffset.x	= (evt.offsetX||evt.layerX) + calcOffsetLeftFromDesktop(bottomResizer);
			mouseOffset.y	= (evt.offsetY||evt.layerY) + calcOffsetTopFromDesktop(bottomResizer);
			
			document.body.style.cursor	= "s-resize";
			
			desktop.setGlassPaneVisibility(true);
			addEventListenerToElement(desktop, "mousemove", verticalResize);
			if(isIELessThan(9))
				addEventListenerToElement(document, "mouseup", stopVerticalResize);
			else
				addEventListenerToElement(window, "mouseup", stopVerticalResize);
			evt.preventDefault();
		}
	};
	var verticalResize = function(/*MouseEvent*/ evt)
	{
		var offsetY	= mouseOffset.y - ((evt.offsetY||evt.layerY) + calcOffsetTopFromDesktop(evt.target));
		var newHeight	= height-offsetY;
		newHeight	= newHeight<0?0:newHeight;
		othis.style.height	= newHeight+"px";
	};
	var stopVerticalResize = function(/*MouseEvent*/ evt)
	{
		document.body.style.cursor	= "";
		
		height	= othis.clientHeight;
		
		desktop.setGlassPaneVisibility(false);
		removeEventListenerToElement(desktop, "mousemove", verticalResize);
		if(isIELessThan(9))
			removeEventListenerToElement(document, "mouseup", stopVerticalResize);
		else
			removeEventListenerToElement(window, "mouseup", stopVerticalResize);
	};
	var stopPropagation = function(/*Event*/ evt)
	{
		evt.stopPropagation();
	};
	(function /*_constructor_end*/()
	{
		if(!othis.getElementsByClassName)
			othis.getElementsByClassName = patchGEBCN.getElementsByClassName;
		shadowDiv.className	= "windowshadow";/*
		addEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "click", drag);/*/
		addEventListenerToElement(othis.getElementsByClassName("titlebar")[0], "mousedown", titleMouseDown);/*
		addEventListenerToElement(othis.getElementsByClassName("minimize")[0], "click", othis.toogleMinimize);
		addEventListenerToElement(othis.getElementsByClassName("maximize")[0], "click", othis.toogleMaximize);
		addEventListenerToElement(othis.getElementsByClassName("close")[0], "click", othis.close);//*/
		var buttons	= othis.getElementsByClassName("titlebar")[0].getElementsByTagName("button");
		for(var i=0 ; i<buttons.length ; i++)
			addEventListenerToElement(buttons[i], "mousedown", stopPropagation);
		addEventListenerToElement(bottomResizer, "mousedown", startVerticalResize);
	})();
}
