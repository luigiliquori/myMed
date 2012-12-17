<? include("header.php"); ?>

	<!-- GOOGLE MAP -->
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&libraries=places"></script>
	<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>
	
	<!-- Google Analytics -->
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-31172274-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	
	<!-- Share this -->
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "f0ca88be-be8a-427a-983a-f670821d7ad2"}); </script>
	
</head>
<body <?= isset($_SESSION['user']) ? 'onload="initialize();"' : ''?> >

	<input type='hidden' id='userID' value='<?= $_SESSION['user']->id ?>' />
	<input type='hidden' id='applicationName' value='myRiviera' />
	<input type='hidden' id='accessToken' value='<?= $_SESSION['accessToken'] ?>' />

<div id="Map" data-role="page" class="page-map">

	<!-- HEADER BAR-->
	<div data-role="header" data-theme="b" data-position="fixed">
		<h1>myFSA</h1>
		<a href="?action=main" data-inline="true" rel="external" data-role="button" data-icon="back">Back</a>
		<a href="?action=localise#searchRoad" data-icon="search" data-iconpos="right" class="ui-btn-right" data-ajax="false">Get directions</a>
	</div>

	<!-- CONTENT -->
	<div data-role="content" class="content-map" style="padding: 0px; margin-top: -20px;">
		
		<!-- MAP -->
		<div id="myRivieraMap"></div>
		
		<div id="steps" data-role="controlgroup" data-type="horizontal" >
			<a id="prev-step" data-role="button" data-icon="arrow-l" title="précédent"></a>
			<a href="#roadMap" data-role="button">Détails</a>
			<a id="next-step" data-role="button" data-icon="arrow-r" data-iconpos="right" title="suivant"></a>
		</div>

	</div>
	
</div>

<? include("SearchRoadView.php"); ?>
<? include("RoadDetailsView.php"); ?>
</body>

