/**
 * @class Drag
 * Ad drag events on an element
 */
function Drag(/*HTMLElement*/ othis, /*HTMLElement*/ parent/*othis.parentNode*/)
{
	var parent = parent||othis.parentNode;
	var mouseOffset = null;
	var hasDrag = false;
	var startdrag = function(/*Event*/ evt)
	{
		hasDrag = false;
		var EVENT_MOUSE_LEFT = 0; // standard W3C (dont IE9)
		if(window.event&&(/MSIE (\d+)/.test(navigator.userAgent))&&(new Number(RegExp.$1)<9)) 	// standard IE (aussi accepté par IE9)
			var EVENT_MOUSE_LEFT = 1;
		if(evt.button == EVENT_MOUSE_LEFT)
		{
			mouseOffset = new Object();
			var ex = othis.offsetLeft;
			var ey = othis.offsetTop;
			mouseOffset.x	= evt.offsetX||evt.layerX+calcOffsetLeft(evt.target, othis);
			mouseOffset.y	= evt.offsetY||evt.layerY+calcOffsetTop(evt.target, othis);
			othis.style.left	= ex+"px";
			othis.style.top		= ey+"px";
			othis.style.zIndex	= "1";
			addEventListenerToElement(parent, "mousemove", drag);
			addEventListenerToElement(window, "mouseup", drop);
			addEventListenerToElement(othis, "click", cancelLink);
			evt.preventDefault();
		}
	};
	var drag = function(/*Event*/ evt)
	{
		hasDrag = true;
		var x	= (evt.offsetX||evt.layerX)+calcOffsetLeft(evt.target, parent);
		var y	= (evt.offsetY||evt.layerY)+calcOffsetTop(evt.target, parent);
		othis.style.left	= (x-mouseOffset.x)+"px";
		othis.style.top		= (y-mouseOffset.y)+"px";
		evt.preventDefault();
	};
	var drop = function(/*Event*/ evt)
	{
		if(hasDrag)
		{
			othis.style.zIndex		= "";
			removeEventListenerToElement(parent, "mousemove", drag);
			removeEventListenerToElement(window, "mouseup", drop);
		}
	};
	var cancelLink = function(/*Event*/ evt)
	{
		if(hasDrag)
			evt.preventDefault();
	};
	var calcOffsetTop = function(/*HTMLElement*/ element, /*HTMLElement*/ parent)
	{
		var offset = 0;
		for( ; element != parent ; element = element.parentNode)
			if(getComputedStyle(element, null).position != "static")
				offset += element.offsetTop;
		return offset
	};
	var calcOffsetLeft = function(/*HTMLElement*/ element, /*HTMLElement*/ parent)
	{
		var offset = 0;
		for( ; element != parent ; element = element.parentNode)
			if(getComputedStyle(element, null).position != "static")
				offset += element.offsetLeft;
		return offset
	};
	//constructor
	{
		addEventListenerToElement(othis, "mousedown", startdrag);
	}
}