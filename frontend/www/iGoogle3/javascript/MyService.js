function MyService(/*string*/ _name, /*MyDesktop*/ _desktop/*=new MyDesktop()*/, /*int*/ _column/*=0*/)
{
	_column = _column||0;
	var _xhr = createCrossXMLHttpRequest();
	var _window;
	/*MyWindow*/	this.getWindow		= function()					{return _window;};
	/*void*/		this.setWindow		= function(/*MyWindow*/ val)	{_window = val;};
	var headLoadListener = function()
	{
		if (_xhr.readyState == 4 && (_xhr.status == 200 || _xhr.status == 0))
		{
			loadHead();
			if(_xhr.addEventListener)
				_xhr.removeEventListener("readystatechange", headLoadListener, false);
			requestBody();
		}
	};
	var requestHead	= function()
	{
		_xhr.open("GET"," ?service="+_name+"&headload", true);
		_xhr.send(null);
		if(_xhr.addEventListener)
			_xhr.addEventListener("readystatechange", headLoadListener, false);
		else
			_xhr.onreadystatechange = headLoadListener;
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
			if(_xhr.addEventListener)
				_xhr.removeEventListener("readystatechange", headLoadListener, false);
		}
	};
	var requestBody	= function()
	{
		_xhr.open("GET","?service="+_name, true);
		_xhr.send(null);
		if(_xhr.addEventListener)
			_xhr.addEventListener("readystatechange", bodyLoadListener, false);
		else
			_xhr.onreadystatechange = bodyLoadListener;
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
		/*
		//exécuter les javacripts
		var scripts	= content.getElementsByTagName("script");
		for(var i=0 ; i<scripts.length ; i++)
		{
			include(scripts[i].src);
			scripts[i].parentNode.removeChild(scripts[i]);i--;
		}//*/
	}
	var loadService	= function(name)
	{
		_name = name;
		requestHead();
	};
	var linkAction = function(evt)
	{
		var url	= evt.currentTarget.getAttribute("href");
		if( (url !== "#") && (url.match(/^[a-z]+:\/\//) !== 0) )// si URL interne
		{
			if(url.match(/^[^?]*\?service=/) !== 0) // si la variable service existe
				loadService(url.replace(/^[^?]*\?service=/,""));
			else
				loadService(url.replace(/^([^?]*\?)?/,_name + "&"));
				
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
				alert("@todo faire l'envoir pour les formulaire en POST\n"+data);
			else
				loadService(_name+"&"+data);
			evt.preventDefault();
		}
	};
    (function /*_constructor*/()
	{
		_window	= newMyWindow(_name, _desktop, _column);
    	loadService(_name);
	})();
	_desktop.addWindow(this.getWindow());
}
