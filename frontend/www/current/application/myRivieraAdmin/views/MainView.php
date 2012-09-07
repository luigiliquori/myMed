<? include("header.php"); ?>

<div id="home" data-role="page">

<? include("header-bar.php"); ?>

	<div data-role="content" class="content">

		<!--TEST -->
		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="false" Style="text-align: left;">
		    
		    <h3>Afficher les points d'interêts</h3>
		    
		    <!-- MAP -->
			<div id="myMap"></div><br />
			
			Longitude: <input id="marker_longitude" type="text" value="" data-inline="true" /><br />
		    Latitude: <input id="marker_latitude" type="text" value="" data-inline="true" /><br />
		    Radius: <input id="marker_radius" type="text" value="" data-inline="true" /><br />
		    Type: <input id="marker_type" type="text" value="" data-inline="true" /><br />
		    <center><a href="#" data-role="button" onclick="printMarkers($('#marker_type').val(), $('#marker_longitude').val(), $('#marker_latitude').val(), $('#marker_radius').val());" rel="external" data-theme="g" data-inline="true">Afficher</a></center>	
		</div>
	
		<!-- Convertion -->
		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
		   
		   <h3>Convertion LongLat/Adresse</h3>
		   
		   Adresse:<br />
		   <input id="converted_address" type="text" value="" /><br />
		   Longitude: <input id="converted_longitude" type="text" value="" data-inline="true" /><br />
		   Latitude: <input id="converted_latitude" type="text" value="" data-inline="true" /><br />
	   	  <center><a href="#" data-role="button" onclick="convertLatLng($('#converted_latitude').val(), $('#converted_longitude').val(), $('#converted_address').val());" data-theme="g" data-inline="true">Convertir</a></center>	
		</div>
		
		<!-- CARF POIs -->
		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			<h3>Ajouter points d'interêts</h3>
			
			<form action="#" method="post" name="addSinglePOI" id="addSinglePOI" enctype="multipart/form-data">
				<input name="addPOI" type="hidden" value="1" />
				<input name="action" type="hidden" value="main" />
			 	Titre: <input name="title" type="text" value="" data-inline="true" /><br />
				Longitude: <input name="longitude" type="text" value="" data-inline="true" /><br />
			    Latitude: <input name="latitude" type="text" value="" data-inline="true" /><br />
			    Type: <input name="type" type="text" value="" data-inline="true" /><br />
			    Description: <textarea name="description" rows="" cols=""></textarea><br />
				<center><a href="#" data-role="button" onclick="document.addSinglePOI.submit()" rel="external" data-theme="g" data-inline="true">Publier</a></center>
			</form>
			
			<div data-role="collapsible" data-theme="d" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			    <h3>Avancé</h3>
				<form action="#" method="post" name="addMultiPOIs" id="addMultiPOIs" enctype="multipart/form-data">
					<input type="hidden" name="addPOI" value="1" />
					<input name="action" type="hidden" value="main" />
					<textarea name="data" rows="" cols=""></textarea>
					 <center><a href="#" data-role="button" onclick="document.addMultiPOIs.submit()" rel="external" data-theme="g" data-inline="true">Publier</a></center>
				</form>
			</div>
		</div>
		
		<!--SUBSCRIBE -->
		<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
			<h3>S'abonner aux commentaires</h3>
			<p>S'abonner aux commentaires de myRiviera:</p>
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>SubscribeForm" id="<?= APPLICATION_NAME ?>SubscribeForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="subscribe" />
				<input type="hidden" name="numberOfOntology" value="1" />
				
				<!-- KEYWORD -->
				<input type="hidden" name="keyword" value="myRivieraTest"/>
				<?php $keywordBean = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keywordBean)); ?>">
			
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()" >Subscribe</a>
			</form>
		</div>
	
	</div>

</div>

<? include("footer.php"); ?>