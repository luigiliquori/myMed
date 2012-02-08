<?php

require_once 'system/templates/application/myRivieraAdmin/MyApplication.class.php';
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
			   <form action="?application=<?= APPLICATION_NAME ?>Admin" method="get" rel="external">
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
			
			<!-- MYMED POIs -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			   <h3>myMed POIs</h3>
			   <div style="color: gray;">
			   		<h4>Example:</h4>
			   		[<br/>
					{"latitude" : "43.7755517", "longitude" : "7.504739",  "title" : "La Cantinella", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"},<br/>
					{"latitude" : "43.775383", "longitude" : "7.5012805",  "title" : "Méditerranée", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"},<br/>
					{"latitude" : "43.7828274", "longitude" : "7.5120867",  "title" : "Napoléon", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"},<br/>
					{"latitude" : "38.42228990140251", "longitude" : "7.5209301",  "title" : "Paris Rome", "icon" : "http://mymed2.sophia.inria.fr/system/templates/application/myRiviera/img/resto.png"}<br/>
					]<br/>
			   </div>
			   <form action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm1" id="<?= APPLICATION_NAME ?>PublishForm1" enctype="multipart/form-data">
					<input type="hidden" name="poisrc" value="mymed" />
					<textarea name="data" rows="" cols=""></textarea>
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm1.submit()">Publish</a>
				</form>
			</div>
			
		
			<!-- CARF POIs -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			    <h3>CARF POIs</h3>
			    <div style="color: gray;">
			   		<h4>Example:</h4>
					[ <br/>
					{
					"type": "FeatureCollection",
					"features": [
					{ "type": "Feature", "properties": { "ADRESSE": "20 rue des Soeurs Munet", "CODE_POSTA": "06500", "VILLE": "menton", "PAYS": "France", "NOM": "ADAPEI", "DESCRIPTIO": "04 93 28 50 3", "SOURCE": "CARF", "DATEDESAIS": "06\/06\/2008", "LAT": "43.774309", "LNG": "7.49246" }, "geometry": { "type": "Point", "coordinates": [ 7.492455, 43.774314 ] } }
					
					]
					}
					,<br/>
					{
					"type": "FeatureCollection",
					"features": [
					{ "type": "Feature", "properties": { "NOM": "ADERF", "ADRESSE": "8 val de Menton", "CODE_POSTA": "06500", "VILLE": "menton", "PAYS": "France", "RESPONSABL": "C�drick H�risson", "PR�sIDENT": "Claude Gaven", "T�l�pHONE": "04 93 57 01 08", "DESCRIPTIO": "Cette association mise en place le 06 ao�t 2001 a pour vocation le d�veloppement �conomique du territoire de la Communaut� d�Agglom�ration de la Riviera Fran�aise", "SOURCE": "CARF", "DE_SAISIE": "02\/02008", "LAT": "43.776666", "LNG": "7.505068" }, "geometry": { "type": "Point", "coordinates": [ 7.505063, 43.776671 ] } }
					
					]
					}<br/>
					]<br/>
			   </div>
				<form action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm2" id="<?= APPLICATION_NAME ?>PublishForm2" enctype="multipart/form-data">
					<input type="hidden" name="poisrc" value="carf" />
					<textarea name="data" rows="" cols=""></textarea>
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm2.submit()">Publish</a>
				</form>
			</div>
			
			<!--TEST -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			    <h3>Tests</h3>
				<form action="#" method="post" name="<?= APPLICATION_NAME ?>TestForm" id="<?= APPLICATION_NAME ?>TestForm" enctype="multipart/form-data">
					Longitude: <input name="longitude" type="text" value="" data-inline="true" /><br />
				    Latitude: <input name="latitude" type="text" value="" data-inline="true" /><br />
				    Radius: <input name="radius" type="text" value="" data-inline="true" /><br />
				    <a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>TestForm.submit()" rel="external">Send</a>
				</form>
			</div>
		</div>
	<?php }
	
}
?>
