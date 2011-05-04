<?php //header("Content-Type:application/xhtml+xml") ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Insert title here</title>
		<link href="style.css" rel="stylesheet" type="text/css" media="all" />
		<link href="design.css" rel="stylesheet" type="text/css" media="screen" />
		<script src="include.js"></script>
		<script src="ecmapatch/EventListener.js"></script>
		<script src="ecmapatch/XMLHttpRequest.js"></script>
		<script src="ecmapatch/getElementsByClassName.js"></script>
		<script src="ecmapatch/importNode.js"></script>
		<script src="ecmapatch/getComputedStyle.js"></script>
		<script src="MyDesktop.js"></script>
		<script src="MyService.js"></script>
		<script src="MyWindow.js"></script>
	</head>
	<body>
		<!--form id="searchBar" method="get" action="">
			<div>
				<span><input type="radio" name="searchType" id="searchType" value="top" /><label for="searchType">Top</label></span>
				<span><input type="radio" name="searchType" id="searchType" value="free" /><label for="searchType">Free</label></span>
			</div>
		</form-->
		<div id="desktop">
            <div class="taskbar"></div>
		</div>
		<div id="footer">Page de teste myMed façon iGoogle</div>
		<script>
		//<![CDATA[
		(function(){
			var desktop = new MyDesktop();
			new MyService("MyTransport", desktop);
			new MyService("test", desktop);
			new MyService("test", desktop);
			new MyService("test", desktop);
			new MyService("test", desktop);
			new MyService("test", desktop);
			new MyService("test", desktop);
//			desktop.getHTMLElement().style.top = "50px";
//			desktop.getHTMLElement().style.left = "50px";
//			function test(evt)
//			{
//				var console = document.getElementById("debug");
//				if(window.event)
//				{
//					console.innerHTML += "ie .event.layerX	= "+event.layerX+"\n";
//					console.innerHTML += "ie .event.layerY	= "+event.layerY+"\n";
//					console.innerHTML += "ie .event.offsetX	= "+event.offsetX+"\n";
//					console.innerHTML += "ie .event.offsetY	= "+event.offsetY+"\n";
//				}
//				if(evt)
//				{
//					console.innerHTML += "w3c.event.layerX	= "+evt.layerX+"\n";
//					console.innerHTML += "w3c.event.layerY	= "+evt.layerY+"\n";
//					console.innerHTML += "w3c.event.offsetX	= "+evt.offsetX+"\n";
//					console.innerHTML += "w3c.event.offsetY	= "+evt.offsetY+"\n";
//				}
//			}
//			desktop.getHTMLElement().onmousemove = test;
//			patchGEBCN.initDocument();
//			document.getElementsByClassName("titlebar").onmousedown = test;
		})();
		//]]>
		</script>
		<pre id="debug"></pre>
	</body>
</html>
