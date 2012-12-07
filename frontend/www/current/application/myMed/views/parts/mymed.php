<!-- --------------------------- -->
<!--		MAIN PAGE			 -->
<!-- --------------------------- -->
<div data-role="page" id="mainPage">

	<!-- --------------------------- -->
	<!--			HEADER			 -->
	<!-- --------------------------- -->
	<div id="header">
		<!-- MENU -->
		<img alt="myMed" src="/application/myMed/img/logo-light.png" height="30px" Style="position: absolute;" />
			
		<div style="position: relative; top: 5px; left: 100px">
			<a href="#mainPage"><b>Home</b></a>
			<a href="#downloadPage">Download</a>
			<a href="http://mymed2.sophia.inria.fr/wiki" data-ajax="false" target="blank">Wiki</a>
			<a href="http://mymed2.sophia.inria.fr/developers" data-ajax="false" target="blank">Developers</a>
			<a href="#login" data-transition="flip">Try</a>
		</div>
		
	</div>
	
	<!-- --------------------------- -->
	<!--			CONTENT			 -->
	<!-- --------------------------- -->
	<div Style="position: relative; top:-130px; left:1%; width: 50%; border: thin black solid; background-color: white;padding: 10px; opacity:0.8;">
		<h2>Welcome to myMed</h2>
		<h3 Style="color: #69a7d2;">
		Mymed is an open source project, which will help you facilitate and accelerate developing mobile applications.</h3>
		<p>Provides engine for publishing, searching and subscribing content, which is built on the top of the distributed database providing high scalability.
		Contains ready features for social web aplication: geolocalisation, points of interest, managing profile, reputation of the content and users.</p>
		<p>Would like to check how fast you can create your application?</p>		
	</div>
	
	<div style="position: absolute; left:60%; top:100px; border: 15px black solid; background-color: #69a7d2; padding: 10px; text-align: center; border-radius: 10px; opacity:0.8;">
		<h3>Download</h3>
		<img alt="myMed" src="/application/myMed/img/dl_icon.png" height="30px"/><br />
		<a href="/application/myMed/dist/mymed_v1.5.zip" data-ajax="false" data-role="button" data-inline="true" data-mini="true">Lastest release: 1.5</a><br /><br />
		Open source, Apache license.<br />
	</div>
	
	<div Style="position: relative; width: 100%; height: 30px; margin-top:-50px; border: thin black solid; background-color: #69a7d2; opacity:0.8; text-align: center;">
		<h3 Style="position: relative; top: -15px;">Overview</h3>
	</div>
	
	<div class="ui-grid-a" Style="padding: 20px;">
		<div class="ui-block-a">
			<h3 Style="color: #69a7d2;">Easy</h3>
			<p>Start from the template, add or remove features, play with the design and you have ready application s</p>
		</div>
		<div class="ui-block-b">
			<h3 Style="color: #69a7d2;">Extensible</h3>
			<p>Mymed provides modular architecture, developers can easily add, remove features whatever they like</p>
		</div>
		<div class="ui-block-a">
			<h3 Style="color: #69a7d2;">Decentralized</h3>
			<p>Project is based on NoSQL database aplication, will be running with 10 or 10 milions of users without any code changes.
			Additionaly is fault tolerance, it does not matter if one or two machines will fail, system will be still running thanks to replication of the data on several servers.</p>
		</div>
	</div><!-- /grid-a -->
	
	
	<!-- --------------------------- -->
	<!--			FOOTER			 -->
	<!-- --------------------------- -->
	<center><div Style="background-color: #69a7d2; height: 1px; width: 80%;"></div></center>
	<div Style="text-align: center;"><?php include("system/views/logos.php") ?></div>
	
</div>

<!-- --------------------------- -->
<!--		DOWNLOAD PAGE		 -->
<!-- --------------------------- -->
<div data-role="page" id="downloadPage">

	<!-- --------------------------- -->
	<!--			HEADER			 -->
	<!-- --------------------------- -->
	<div id="header">
		<!-- MENU -->
		<img alt="myMed" src="/application/myMed/img/logo-light.png" height="30px" Style="position: absolute;" />
			
		<div style="position: relative; top: 5px; left: 100px">
			<a href="#mainPage">Home</a>
			<a href="#downloadPage"><b>Download</b></a>
			<a href="http://mymed2.sophia.inria.fr/wiki" data-ajax="false" target="blank">Wiki</a>
			<a href="http://mymed2.sophia.inria.fr/developers" data-ajax="false" target="blank">Developers</a>
			<a href="#login" data-transition="flip">Try</a>
		</div>
		
	</div>
	
	<!-- --------------------------- -->
	<!--			CONTENT			 -->
	<!-- --------------------------- -->
	<div Style="position: relative; top:-130px; left:1%; width: 48%; border: thin black solid; background-color: white; padding: 10px; opacity:0.8;">
		<h2>How to get myMed</h2>
		myMed is available open-source under the <a href="http://www.apache.org/licenses/LICENSE-2.0.html" target="blank">Apache license</a>. 
		There are 2 ways you can get it:
		
		<h3>Option 1. Get the latest official version</h3>
		<p>The latest official version is 1.5. availble here: <a href="/application/myMed/dist/mymed_v1.5.zip" data-ajax="false">mymed_v1.5.zip</a></p>
		
		<h3>Option 2. Get latest development version</h3>
		<p>The latest and greatest myMed version is the one that's in our Git repository (our revision-control system). 
		Get it using this shell command, which requires <a href="http://git-scm.com/">Git</a>:</p>
		<p>The project can be checked out through anonymous access with the following command.</p>
		<p><tt>git clone https://gforge.inria.fr/git/mymed/mymed.git</tt></p>
		<p><tt>git clone git://scm.gforge.inria.fr/mymed/mymed.git</tt></p>
		
		<h3>After you get it</h3>
		<p>See the <a href="http://mymed2.sophia.inria.fr/wiki" data-ajax="false" target="blank">installation guide</a> for further instructions.</p>
		<p>And be sure to sign up for the <a href="https://lists.gforge.inria.fr/mailman/listinfo/mymed-develop" target="blank">mymed-develop mailing list</a>, 
		where other myMed users and the myMed developers themselves all hang out to help each other.</p>
		
	</div>
	
	<div style="position: absolute; left:51%; width:47%; top:100px; border: 15px black solid; border: thin black solid; background-color: white; padding: 10px; opacity:0.8;">
		<h3>Dependencies</h3>
		In order to run the mymed infrastructure it is necessary to install different servers and applications that are necessary to run and serve the website: 
		<ul>
			<li>Apache2 Web Server</li>
			<li><a href="http://glassfish.java.net/" data-ajax="false" target="blank">Glassfish 3.1.1</a> Application Server</li>
			<li><a href="http://cassandra.apache.org/" data-ajax="false" target="blank">Cassandra</a> database</li>
			<li>Java 1.6 or higher, PHP, curl, ant, and optionally jsvc</li>
		</ul>
	</div>
		
	<!-- --------------------------- -->
	<!--			FOOTER			 -->
	<!-- --------------------------- -->
	<div Style="position: relative; top:-100px;">
	<center><div Style="background-color: #69a7d2; height: 1px; width: 80%;"></div></center>
	<div Style="text-align: center;"><?php include("system/views/logos.php") ?></div>
	</div>
	
</div>