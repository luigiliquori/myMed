function FormSerializer(/* FormHTMLElement */form)
{
	// object from Prototype Library
	var Serializers =
	{
		button : function(element)
		{
			return null;
		},
		input : function(element)
		{
			switch(element.type.toLowerCase()){
				case 'button':
				case 'submit':
				case 'reset':
					return null;
				case 'checkbox':
				case 'radio':
					return this.inputSelector(element);
				default:
					return this.textarea(element);
			}
	 	},
	 	inputSelector : function(element)
	 	{
	 		return element.checked ? element.value : null;
	 	},
	 	textarea : function(element)
	 	{
	 		return element.value;
	 	},
	 	select : function(element)
	 	{
	 		return this[element.multiple? 'selectMany' : 'selectOne'](element);
	 	},
	 	selectOne : function(element)
	 	{
	 		var index = element.selectedIndex;
	 		return index >= 0 ? this.optionValue(element.options[index]) : null;
	 	},
	 	selectMany : function(element)
	 	{
	 		var values, length = element.length;
	 		if(!length)
	 			return null;
	 		for( var i = 0, values = [] ; i < length ; i++ )
	 		{
	 			var opt = element.options[i];
	 			if(opt.selected)
	 				values.push(this.optionValue(opt));
	 		}
	 		return values;
	 	},
	 	optionValue : function(opt)
	 	{
	 		return opt.hasOwnProperty('value') ? opt.value : "";
	 	}
	}
	this.getValue = function(/* InputHTMLElement|SelectHTMLElement|TextareaHTMLElement */element)
	{
		return Serializers[element.tagName.toLowerCase()](element);
	}
	this.serialize = function(/* string */ submitButtonName)
	{
		var strSerialize = "";
		for( var i=0 ; i<form.elements.length ; i++)
		{
			var value = this.getValue(form.elements[i]);
			if(value !== null&&form.elements[i].name)
			{
				if(strSerialize !== "")
					strSerialize += "&";
				strSerialize += encodeURIComponent(form.elements[i].name) + "=" + encodeURIComponent(value);
			}
		}
		if(submitButtonName && submitButtonName.name)
		{
			if(strSerialize !== "")
				strSerialize += "&";
			strSerialize += encodeURIComponent(submitButtonName.name) + "=" + encodeURIComponent(submitButtonName.value);
		}
		return strSerialize;
	}
}