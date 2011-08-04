var zoom = 
{
	"ZoomNb"	: 0,
	"Zoom"		: function(/*int*/ currentZoom/*=100*/, /*HTMLDocument*/ doc/*=document*/)
	{
		doc = doc?doc:document;
		currentZoom = currentZoom?currentZoom:100;
		function /*void*/ setFontSize(/*string*/ size)
		{
			doc.body.style.fontSize = size;
		};
		this.setFontSize = /*void*/ function(/*string*/ size)
		{
			setFontSize(size);
		};
		function /*boolean*/ isUint(/*string*/ string)
		{
			return (string.toString().search(/^[0-9]+$/) == 0);
		};
		function /*string*/ getEventChar(/*Event*/ evt)
		{
			if(evt.charCode)
				return String.fromCharCode(evt.charCode);
			else if(evt.keyCode)
				return String.fromCharCode(evt.keyCode);
			else if(evt.which)
				return String.fromCharCode(evt.which);
			return undefined;
		};
		function /*void*/ addIncDecButtons(/*HTMLElementInput*/ input)
		{
			function /*void*/ incDec(/*int*/ step)
			{
				if(isUint(input.value))
					input.value = parseInt(input.value)+step;
				else
					input.value = 12;
				input.onchange();
			};
			var incButton = doc.createElement("button");
			incButton.innerHTML = "+";
			incButton.onclick = function(){incDec(1);return false;};
			var decButton = doc.createElement("button");
			decButton.innerHTML = "-";
			decButton.onclick = function(){incDec(-1);return false;};
			if(input.nextSibling)
				input.parentNode.insertBefore(incButton, input.nextSibling);
			else
				input.parentNode.appendChild(incButton);
			input.parentNode.insertBefore(decButton, input);
		};
		(function()
		{
			var form	= doc.createElement("form");
			var div		= doc.createElement("div");
			var label	= doc.createElement("label");
			var input	= doc.createElement("input");
			form	.setAttribute("class"	, "zoom");
			label	.setAttribute("for"		, "zoomCommand");
			input	.setAttribute("type"	, "range");
			input	.setAttribute("id"		, "zoomCommand");
			input	.setAttribute("min"		, "10");
			input	.setAttribute("max"		, "200");
			input	.setAttribute("value"	, currentZoom);
			input	.setAttribute("maxlength"	, "3");
			input	.setAttribute("size"	, "2");
			form	.appendChild(div);
			div		.appendChild(label);
			div		.appendChild(input);
			input.onchange		= function(){	setFontSize(this.value+'%');};
			input.onkeypress	= function(){	if(!isUint(getEventChar(evt))) return false;else return true;};
			input.onkeyup		= function(){	if(!isUint(this.value))this.onchange();};
			input.onfocus		= function(){	this.select();};
			addIncDecButtons(input);
			doc.body.insertBefore(form, doc.body.firstChild);
			zoom.ZoomNb++;
		})();
	}
}