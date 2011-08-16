<div id="home" data-role="page" data-theme="a">
	
	<!-- HEADER -->
	<div id="header" data-role="header">
		<h1>myMed application</h1>
	</div>

	<!-- CONTENT -->
	<div id="content" data-role="content" id="one">
	
		<a href="#home" data-role="button" class="ui-btn-active" data-inline="true">Favoris</a>
		<a href="#category" data-role="button" data-inline="true">Catégories</a>
		<a href="#top10" data-role="button" data-inline="true">Top 10</a>
		
		<br />
		<hr>
		<br />
		
    	<a href="#myTransport" data-rel="dialog"><img alt="myTransport" src="img/icon/new/myTransport.png" ></a>
	    <a class="myIcon"><img alt="myAngel" src="img/icon/new/myAngel.png" ></a>
	    <a class="myIcon"><img alt="myMontagne" src="img/icon/new/myMontagne.png" ></a>
	    <a class="myIcon"><img alt="myInfo" src="img/icon/new/myInfo.png" ></a>
	    <a class="myIcon"><img alt="myKayak" src="img/icon/new/myKayak.png" ></a>
	    <a class="myIcon"><img alt="myLocalProduc" src="img/icon/new/myLocalProduc.png" ></a>
		
	</div>
	
	<!-- FOOTER_PERSITENT-->
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" class="ui-btn-active">Home</a></li>
				<li><a href="#myProfile">Profile</a></li>
				<li><a href="#login">Deconnexion</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->
			
</div>

<!-- CATEGORY -->
<div id="category" data-role="page" data-theme="a">
	
	<!-- HEADER -->
	<div id="header" data-role="header">
		<h1>myMed application</h1>
	</div>

	<!-- CONTENT -->
	<div id="content" data-role="content" id="one">
	
		<a href="#home" data-role="button" data-inline="true">Favoris</a>
		<a href="#category" data-role="button" class="ui-btn-active" data-inline="true">Catégories</a>
		<a href="#top10" data-role="button" data-inline="true">Top 10</a>
		
		<br /><br />
		
		<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a">
			<li data-role="list-divider">Transport</li>
			<li><img alt="myTransport" src="img/icon/new/myTransportSMALL.png" /><a href="#myTransport" data-rel="dialog">MyTranpsort</a></li>
			<li><img alt="myAngel" src="img/icon/new/myAngelSMALL.png" ><a>myAngel</a></li>
			<li data-role="list-divider">Toursime</li>
			<li><img alt="myMontagne" src="img/icon/new/myMontagneSMALL.png" ><a>myMontagne</a></li>
			<li><img alt="myInfo" src="img/icon/new/myInfoSMALL.png" ><a>myInfo</a></li>
			<li data-role="list-divider">Alimentation</li>
			<li><img alt="myLocalProduc" src="img/icon/new/myLocalProducSMALL.png"><a>myLocalProduc</a></li>
		</ul>

		
	</div>
	
	<!-- FOOTER_PERSITENT-->
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" class="ui-btn-active">Home</a></li>
				<li><a href="#myProfile">myProfile</a></li>
				<li><a href="#login">Deconnexion</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->
			
</div>

<!-- TOP10 -->
<div id="top10" data-role="page" data-theme="a">
	
	<!-- HEADER -->
	<div id="header" data-role="header">
		<h1>myMed application</h1>
	</div>

	<!-- CONTENT -->
	<div id="content" data-role="content" id="one">
	
		<a href="#home" data-role="button" data-inline="true">Favoris</a>
		<a href="#category" data-role="button" class="ui-btn-active" data-inline="true">Catégories</a>
		<a href="#top10" data-role="button" data-inline="true">Top 10</a>
		
		<br /><br />
		
		<ol data-role="listview" data-theme="c" data-dividertheme="a">
			<li><img alt="myTransport" src="img/icon/new/myTransportSMALL.png" /><a href="#myTransport" data-rel="dialog">MyTranpsort</a></li>
			<li><img alt="myAngel" src="img/icon/new/myAngelSMALL.png" ><a>myAngel</a></li>
			<li><img alt="myMontagne" src="img/icon/new/myMontagneSMALL.png" ><a>myMontagne</a></li>
			<li><img alt="myInfo" src="img/icon/new/myInfoSMALL.png" ><a>myInfo</a></li>
			<li><img alt="myLocalProduc" src="img/icon/new/myLocalProducSMALL.png"><a>myLocalProduc</a></li>
		</ol>

		
	</div>
	
	<!-- FOOTER_PERSITENT-->
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" class="ui-btn-active">Home</a></li>
				<li><a href="#myProfile">myProfile</a></li>
				<li><a href="#login">Deconnexion</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->
			
</div>