/**
 * a crossbrowser function to replace addEventListener
 * listener function on IE can be used as on other browser.
 * can't be used on XMLHTTPRequest for IE because attachEvent doesn't exists
 * @target	target on which attache the event
 * @type	like first atttribut in addEventListener() ("click", "keyup", "keydown"...)
 * @listener	like second atttribut in addEventListener() (function called by event
 * @useCapture can't be used because of Inernet Explorer implementation
 */
function addEventListenerToElement(/*HTMLElement*/ target, /*string*/ type, /*Function*/ listener)
{
	if(!target.listeners)
		target.listeners = new Array();
	if(!target.listeners[type])
		target.listeners[type] = new Array();
	function eventListener(/*Event*/ evt)
	{
		evt = evt?evt:window.event;
		if(!evt.target)
			evt.target = event.srcElement;
		if(!evt.currentTarget)
			evt.currentTarget = target;
		if(!evt.preventDefault)
			evt.preventDefault = function(){event.returnValue = false;};
		if(!evt.stopPropagation)
			evt.stopPropagation = function(){event.cancelBubble = true;};
		listener(evt);
	}
	target.listeners[type][listener] = eventListener;
	if(target.addEventListener)
		target.addEventListener(type, eventListener, false);
	else
		target.attachEvent("on"+type, eventListener);
}
/**
 * a crossbrowser function to replace removeEventListener 
 * for listener attached with addEventListenerToElement
 * listener function on IE can be used as on other browser.
 * @target	target on which attache the event
 * @type	like first atttribut in addEventListener() ("click", "keyup", "keydown"...)
 * @listener	like second atttribut in addEventListener() (function called by event
 * @useCapture can't be used because of Inernet Explorer implementation
 */
function removeEventListenerToElement(/*HTMLElement*/ target, /*string*/ type, /*Function*/ listener)
{
	if(target.addEventListener)
		target.removeEventListener(type, target.listeners[type][listener], false);
	else
		target.detachEvent("on"+type, target.listeners[type][listener]);
}