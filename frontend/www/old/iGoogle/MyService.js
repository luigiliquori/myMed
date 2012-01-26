function MyService(/*string*/ name, /*MyDesktop*/ desktop/*=new MyDesktop()*/)
{
	var window;
	/*MyWindow*/	this.getWindow		= function()					{return window;};
	/*void*/		this.setWindow		= function(/*MyWindow*/ val)	{window = val;};
	var loadHead	= function(/*XMLHttpRequest*/ xhr)
	{
		xhr.open("GET"," service.php?service="+name+"&headload", false);
		xhr.send(null);
		if(xhr.responseText)
		{
			var content	= document.importNode(xhr.responseXML.documentElement, true);
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
	var loadService	= function(name)
	{
		var xhr = createCrossXMLHttpRequest();
		loadHead(xhr);
		xhr.open("GET", "service.php?service="+name, false);
		xhr.send(null);
		var content	= document.importNode(xhr.responseXML.documentElement, true);
		
		//surcharger les liens
		var links	= content.getElementsByTagName("a");
		for(var i=0 ; i<links.length ; i++)
			addEventListenerToElement(links[i], "click", linkAction);
		
		//surcharger les formulaires
		var forms	= content.getElementsByTagName("form");
		for(var i=0 ; i<forms.length ; i++)
			addEventListenerToElement(forms[i], "submit", formSubmit);
		
		window.setContent(content);
		
		//exécuter les javacripts
		var scripts	= content.getElementsByTagName("script");
		for(var i=0 ; i<scripts.length ; i++)
		{
			include(scripts[i].src);
			scripts[i].parentNode.removeChild(scripts[i]);
		}
	};
	var linkAction = function(evt)
	{
		var url	= evt.currentTarget.getAttribute("href");
		if(url.match(/^[a-z]+:\/\//) !== 0)// si URL interne
		{
			loadService(url.replace(/^[^?]*\?service=/,""));
			evt.preventDefault();
		}
	};
	var formSubmit = function(evt)
	{
		var form	= evt.currentTarget;
		var url		= form.getAttribute("action");
		if(url.match(/^[a-z]+:\/\//) !== 0)// si URL interne
		{
			url = url.replace(/^[^?]*\?service=/,"");
			
			evt.preventDefault();
		}
	};
    (function /*_constructor*/()
	{
		window	= newMyWindow(name, desktop);
    	loadService(name);
	})();
	desktop.addWindow(this.getWindow());
}
