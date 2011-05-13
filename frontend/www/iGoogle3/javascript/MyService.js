function MyService(/*string*/ _name, /*MyDesktop*/ _desktop/*=new MyDesktop()*/, /*int*/ _column/*=0*/)
{
	_column = _column||0;
	var _xhr = createCrossXMLHttpRequest();
	var _window;
	var _url	= "?ajax&service="+_name;
	/*MyWindow*/	this.getWindow		= function()					{return _window;};
	/*void*/		this.setWindow		= function(/*MyWindow*/ val)	{_window = val;};
	var headLoadListener = function()
	{
		if (_xhr.readyState == 4 && (_xhr.status == 200 || _xhr.status == 0))
		{
			loadHead();
			//if(_xhr.removeEventListener)
			//	removeEventListenerToElement(_xhr, "readystatechange", headLoadListener);
			//else
				delete _xhr.onreadystatechange;
			requestBody();
		}
	};
	var requestHead	= function()
	{
		_xhr.open("GET",_url+"&headload", true);
		//if(_xhr.addEventListener)
		//	addEventListenerToElement(_xhr, "readystatechange", headLoadListener);
		//else
			_xhr.onreadystatechange = headLoadListener;
		_xhr.send(null);
	};
	var loadHead	= function()
	{
		if(_xhr.responseText)
		{
			var content	= document.importNode(_xhr.responseXML.documentElement, true);
			for(var i=0 ; i<content.children.length ; i++)
			{
				if(content.children[i].nodeName.toLowerCase() == "script")
					include(content.children[i].src);// afin d'exécuter les javascripts
				else
				{
					document.getElementsByTagName("head")[0].appendChild(content.children[i]);
					i--;
				}
			}
		}
	};
	var bodyLoadListener = function()
	{
		if (_xhr.readyState == 4 && (_xhr.status == 200 || _xhr.status == 0))
		{
			loadBody();
			//if(_xhr.removeEventListener)
			//	removeEventListenerToElement(_xhr, "readystatechange", headLoadListener);
			//else
			delete _xhr.onreadystatechange;
		}
	};
	var requestBody	= function()
	{
		_xhr.open("GET",_url, true);
		//if(_xhr.addEventListener)
		//	addEventListenerToElement(_xhr, "readystatechange", bodyLoadListener);
		//else
			_xhr.onreadystatechange = bodyLoadListener;
		_xhr.send(null);
	};
	var loadBody	= function()
	{
		var content	= document.importNode(_xhr.responseXML.documentElement, true);
		
		//surcharger les liens
		var links	= content.getElementsByTagName("a");
		for(var i=0 ; i<links.length ; i++)
		{
			if(links[i].getAttribute("onclick") != null)
				addEventListenerToElement(links[i], "click", links[i].onclick);
			addEventListenerToElement(links[i], "click", linkAction);
		}
		
		//surcharger les formulaires
		var forms	= content.getElementsByTagName("form");
		for(var i=0 ; i<forms.length ; i++)
		{
			if(forms[i].getAttribute("onsubmit") != null)
				addEventListenerToElement(forms[i], "submit", forms[i].onsubmit);
			addEventListenerToElement(forms[i], "submit", formSubmit);
		}
		
		_window.setContent(content);
		//*
		//exécuter les javacripts
		var scripts	= content.getElementsByTagName("script");
		for(var i=0 ; i<scripts.length ; i++)
		{
			if(scripts[i].src)
				include(scripts[i].src);
			else
				eval(scripts[i].innerHTML);
			scripts[i].parentNode.removeChild(scripts[i]);i--;
		}//*/
	}
	var loadService	= function(url)
	{
		_url = url;
		requestHead();
	};
	var linkAction = function(evt)
	{
		var url	= evt.currentTarget.getAttribute("href");
		if( (url !== "#") && !url.match(/^[a-z]+:\/\//) )// si URL interne
		{
			if(url.match(/^[^?]*\?service=/) !== 0) // si la variable service existe
				loadService(url.replace(/^[^?]*/,""));
			else
				loadService("?ajax&service="+url.replace(/^([^?]*\?)?/,_name + "&"));
				
			evt.preventDefault();
		}
	};
	var formSubmit = function(evt)
	{// @todo envoyer par iFrame (en utilisant target) http://www.siteduzero.com/tutoriel-3-4710-iframe-loading.html
		var form	= evt.currentTarget;
		var url		= form.action;
		if( (url !== "#") && (url.match(/^[a-z]+:\/\//) !== 0) )// si URL interne
		{
			url = url.replace(/^[^?]*\?service=/,"");
			var data = new FormSerializer(form).serialize();
			if(form.method.toUpperCase() == "POST")
				alert("@todo faire l'envoit pour les formulaire en POST\n"+data);
			else
				loadService("?ajax&service="+_name+"&"+data);
			evt.preventDefault();
		}
	};
    (function /*_constructor*/()
	{
		_window	= newMyWindow(_name, _desktop, _column);
		_window.setIcon("services/"+_name+"/icon.png");
    	loadService("?ajax&service="+_name);
	})();
	_desktop.addWindow(this.getWindow());
}
