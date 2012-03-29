<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';
require_once 'lib/dasp/request/Request.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class PublishView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $myRivieraPOIs_myMed;
	private /*String*/ $myRivieraPOIs_carf;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("myRiviera");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
		
			<!-- TOOLs -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			   <h3>Tools</h3>
			   <form action="?application=<?= APPLICATION_NAME ?>" method="get" rel="external">
				   	<?php 
				 	  	$address = "";
						$longitude = "";
						$latitude = "";
						if(isset($_GET['address']) && $_GET['address'] != ""){
							// CALL TO GOOGLE GEOCODE API
							$geocode = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_GET['address']) . "&sensor=true"));
							if($geocode->status == "OK"){
								$longitude = $geocode->results[0]->geometry->location->lng;
								$latitude = $geocode->results[0]->geometry->location->lat;
							}
						} else if(isset($_GET['longitude']) && isset($_GET['latitude'])){
							$geoloc = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$_GET['latitude'].",".$_GET['longitude']."&sensor=true"));
							$address = $geoloc->results[0]->formatted_address;
						}
					?>
				   Address:<br />
				   <input name="address" type="text" value="<?= $address ?>" /><br />
				   Longitude: <input name="longitude" type="text" value="<?= $longitude ?>" data-inline="true" /><br />
				   Latitude: <input name="latitude" type="text" value="<?= $latitude ?>" data-inline="true" /><br />
				   <input type="submit" data-role="button" value="Convert" rel="external"/>
			   </form>
			</div>
			
			<!-- MYMED POIs 
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			   <h3>myMed POIs</h3>
			   <div style="color: gray;">
			   		<h4>Example:</h4>
			   		[<br/>
					{"latitude" : "43.7755517", "longitude" : "7.504739",  "title" : "La Cantinella", "description" : "restaurant", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"},<br/>
					{"latitude" : "43.775383", "longitude" : "7.5012805",  "title" : "Méditerranée", "description" : "restaurant", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"},<br/>
					{"latitude" : "43.7828274", "longitude" : "7.5120867",  "title" : "Napoléon", "description" : "restaurant", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"},<br/>
					{"latitude" : "38.42228990140251", "longitude" : "7.5209301",  "title" : "Paris Rome", "description" : "restaurant", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"}<br/>
					]<br/>
			   </div>
			   <form action="#" method="post" name="Test2Form" id="Test2Form" enctype="multipart/form-data">
					<input type="hidden" name="poisrc" value="mymed" />
					<textarea name="data" rows="" cols=""></textarea>
					<a href="#" data-role="button" onclick="document.Test2Form.submit()" rel="external">Publish</a>
				</form>
			</div> -->
			
		
			<!-- CARF POIs -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			    <h3>Add POIs</h3>
			    <div style="color: gray;">
			   		<h4>Example:</h4>
					[<br/>
					{<br/>
					"type": "FeatureCollection",<br/>
					"features": [<br/>
					{ "type": "Feature", "properties": { "Type": "ADAPEI_EPSG4326", "Nom": "ADAPEI", "Description": "04 93 28 46 12", "Adresse": "20 rue des Soeurs Munet 06500 Menton", "Id_media": 0 }, "geometry": { "type": "Point", "coordinates": [ 7.492455, 43.774314 ] } }
					
					]<br/>
					}<br/>
					,<br/>
					{<br/>
					"type": "FeatureCollection",<br/>
					"features": [<br/>
					{ "type": "Feature", "properties": { "Type": "ADERF_EPSG4326", "Nom": "ADERF", "Description": "Cette association mise en place le 06 août 2001 a pour vocation le développement économique du territoire de la Communauté d’Agglomération de la Riviera Française", "Adresse": "8 val de Menton 06500 Menton", "Id_media": 0 }, "geometry": { "type": "Point", "coordinates": [ 7.501797, 43.776644 ] } }
					
					]<br/>
					}<br/>
					,<br/>
					{<br/>
					"type": "FeatureCollection",<br/>
					"features": [<br/>
					{ "type": "Feature", "properties": { "Type": "ASSEDIC_EPSG4326", "Nom": "ASSEDIC", "Description": "Les ASSEDIC sont ouverts le lundi, de 9h00 à 15h15, le mardi de 9h00 à 14h00, et du mercredi au vendredi  de 9h00 à 15h15", "Adresse": "8 rue Guyau 06500 MENTON", "Id_media": 0 }, "geometry": { "type": "Point", "coordinates": [ 7.504539, 43.776494 ] } }
					
					]<br/>
					}<br/>
					]<br/>
			   </div>
				<form action="#" method="post" name="TestForm3" id="TestForm3" enctype="multipart/form-data">
					<input type="hidden" name="poisrc" value="carf" />
					<textarea name="data" rows="" cols=""></textarea>
					 <a href="#" data-role="button" onclick="document.TestForm3.submit()" rel="external">Publish</a>
				</form>
			</div>
			
			<!--TEST -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			    <h3>Tests</h3>
				<form action="#" method="post" name="TestForm4" id="TestForm4" enctype="multipart/form-data">
					Longitude: <input name="longitude" type="text" value="" data-inline="true" /><br />
				    Latitude: <input name="latitude" type="text" value="" data-inline="true" /><br />
				    Radius: <input name="radius" type="text" value="" data-inline="true" /><br />
				    <a href="#" data-role="button" onclick="document.TestForm4.submit()" rel="external">Send</a>
				</form>
			</div>
		</div>
	<?php }
	
}
?>
