<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView1 extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Find1");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	private /*Response*/ function getPosition(/*String*/ $id, /*String*/ $type){
		$request = new Request("DHTRequestHandler", READ);
		$request->addArgument("key", $id . $type);
		$responsejSon = $request->send();
		return json_decode($responsejSon);
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm1" id="<?= APPLICATION_NAME ?>FindForm1">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="4" />
				
				<div class="ui-grid-b">
					
					<!-- FROM -->
					<div class="ui-block-a">
					<img id="dpicture" alt="thumbnail" src="http://graph.facebook.com/007/picture?type=large" width="80" height="80" style="" /><br />
					<select id="selectDepart" name="enum" data-theme="a" onchange="changeDestination('depart')">
                                                
						<option value="nullpart&&007">Choisir</option>

						<?php
						// MY POSITION
						$responseObject = $this->getPosition($_SESSION['user']->name, "latitude");
						if($responseObject->status == 200) {
							$latitude = $responseObject->data->value;
							$responseObject = $this->getPosition($_SESSION['user']->name, "longitude");
							if($responseObject->status == 200) {
								$longitude = $responseObject->data->value;
								$geoloc = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true")); ?>
								<option
									value="<?= $geoloc->results[0]->formatted_address ?>&&<?= $_SESSION['user']->profilePicture ?>">
									<?= $_SESSION['user']->name ?>
								</option>
								<?php
							}
						}

						// FRIENDS POSITION
						if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { 
							$responseObject = $this->getPosition($friend["name"], "latitude");
							if($responseObject->status == 200) {
								$responseObject = $this->getPosition($friend["name"], "longitude");
								if($responseObject->status == 200) {
									$longitude = $responseObject->data->value;
									// CALL TO GOOGLE GEOCODE API
									$geoloc = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true"));
									?>
									<option
										value="<?= $geoloc->results[0]->formatted_address ?>&&<?= "http://graph.facebook.com/" . $friend["id"] . "/picture?type=large" ?>">
										<?= $friend["name"] ?>
									</option>
									<?php
								}
							}
						}
					} ?>
					</select>
					</div>
					<input id="depart" type="hidden" name="Départ" value=""  />

					<!-- SEPARATOR -->
					<div class="ui-block-b">
						<img alt="thumbnail" src="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png" width="80" height="80" style="" />				
					</div>

					<!-- TO -->
					<div class="ui-block-c">
					<img id="apicture" alt="thumbnail" src="http://graph.facebook.com/007/picture?type=large" width="80" height="80" style="" /><br /> 
					<select id="selectArrivee" name="enum" data-theme="a" onchange="changeDestination('arrivee')">

						<option value="nullpart&&007">Choisir</option>
					
						<?php
						// MY POSITION
						$responseObject = $this->getPosition($_SESSION['user']->name, "latitude");
						if($responseObject->status == 200) {
							$latitude = $responseObject->data->value;
							$responseObject = $this->getPosition($_SESSION['user']->name, "longitude");
							if($responseObject->status == 200) {
								$longitude = $responseObject->data->value;
								$geoloc = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true")); ?>
								<option
									value="<?= $geoloc->results[0]->formatted_address ?>&&<?= $_SESSION['user']->profilePicture ?>">
									<?= $_SESSION['user']->name ?>
								</option>
								<?php
							}
						}
			
						// FRIENDS POSITION
						if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { 
							$responseObject = $this->getPosition($friend["name"], "latitude");
							if($responseObject->status == 200) {
								$responseObject = $this->getPosition($friend["name"], "longitude");
								if($responseObject->status == 200) {
									$longitude = $responseObject->data->value;
									// CALL TO GOOGLE GEOCODE API
									$geoloc = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true"));
									?>
									<option
										value="<?= $geoloc->results[0]->formatted_address ?>&&<?= "http://graph.facebook.com/" . $friend["id"] . "/picture?type=large" ?>">
										<?= $friend["name"] ?>
									</option>
									<?php
								}
							}
						}
					} ?>
                    </select>
					</div>
					<input id="arrivee" type="hidden" name="Arrivée" value=""  />
					
				</div>
				<br />
				<!-- DATE -->
				Partir le :<br />
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="a"/>
				
				<br /><br />
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm1.submit()">Calculer l'itinéraire</a>	
			</form>
		</div>
	<?php }
	
}
?>
