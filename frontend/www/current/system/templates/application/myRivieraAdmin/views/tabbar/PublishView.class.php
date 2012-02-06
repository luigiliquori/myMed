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
		parent::__construct("myRivieraAdmin");
		// USER POINT
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "keyword(myRivieraPOIs_myMed)");
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$this->myRivieraPOIs_myMed = "";
		if($responseObject->status == 200) {
			$resArray = json_decode($responseObject->data->results);
			$this->myRivieraPOIs_myMed = $resArray[0]->data;
		}
		
		// CARF POINT
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "keyword(myRivieraPOIs_carf)");
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$this->myRivieraPOIs_carf = "";
		if($responseObject->status == 200) {
			$resArray = json_decode($responseObject->data->results);
			$this->myRivieraPOIs_carf = $resArray[0]->data;
		}
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
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="false" Style="text-align: left;">
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
			
			<!-- MYMED POIs -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true">
			   <h3>myMed POIs</h3>
			   <form action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm1" id="<?= APPLICATION_NAME ?>PublishForm1" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="2" />
					
					<!-- KEYWORD -->
					<input type="hidden" name="keyword" value="myRivieraPOIs_myMed"/>
					<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
					
					<textarea name="data" rows="" cols=""><?= $this->myRivieraPOIs_myMed ?></textarea>
					<?php $text = new MDataBean("data", null, TEXT); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($text)); ?>">
					<br />
					
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm1.submit()">Publish</a>
				</form>
			</div>
			
		
			<!-- CARF POIs -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="true">
			   <h3>CARF POIs</h3>
				<form action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm2" id="<?= APPLICATION_NAME ?>PublishForm2" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="2" />
					
					<!-- KEYWORD -->
					<input type="hidden" name="keyword" value="myRivieraPOIs_carf"/>
					<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
					
					<!-- TEXT -->
					<textarea name="data" rows="" cols=""><?= $this->myRivieraPOIs_carf ?></textarea>
					<?php $text = new MDataBean("data", null, TEXT); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($text)); ?>">
					<br />
					
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm2.submit()">Publish</a>
				</form>
			</div>
		</div>
	<?php }
	
}
?>
