<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct("Find");
		$this->handler = $handler;
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<h1><?= TARGET == "mobile" ? " " : APPLICATION_NAME ?></h1>
			<a id="Iti" data-role="actionsheet" data-sheet="Itin" data-icon="search" class="ui-btn-left">Itinéraire</a> 
			
			<div class="ui-btn-right">
				<select name="select-filter" id="select-filter" multiple="multiple" data-native-menu="false" onchange="updateFilter()">
					<option>Points d'interêts</option>
					<option value="mymed">myMed</option>
					<option value="carf">carf</option>
					<option value="cityway">cityway</option>
				</select>
			</div>
			<!-- <a href="#Option" data-role="button" data-rel="dialog" class="ui-btn-right" data-icon="gear">Options</a>  -->
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div data-role="content" style="padding: 0px;">
			
			<div id="myRivieraMap"></div>

			<?php if ($this->handler->getSuccess()) { ?> 				<!-- FROM CITYWAY -->
				<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "50" ?>%;">
					<h3>Feuille de route</h3>
					<!-- ITINERAIRE -->
					<script type="text/javascript">setTimeout("calcRouteFromCityWay('<?= $this->handler->getSuccess()->kmlurl ?>')", 500);</script>
					<ul data-role="listview" data-inset="true" data-theme="d" data-divider-theme="b">
						<?php $listDivider = null;?>
						<?php $i=0 ?>
						<?php foreach($this->handler->getSuccess()->itineraire->ItineraryObj->tripSegments->tripSegment as $tripSegment) { ?>
		
							<?php if($listDivider == null || $listDivider != $tripSegment->type) { ?>
								<li data-role="list-divider"><?php 
									if($tripSegment->type == "WALK") { ?>
										<!-- <img alt="Marche" src="system/templates/application/myRiviera/img/<?= strtolower($tripSegment->type) ?>.png" /> -->
										<span>Marche</span>
									<?php } else if($tripSegment->type == "CONNECTION") { ?>
										<span>Connection</span>
									<?php } else  { ?>
										<!-- <img alt="Marche" src="system/templates/application/myRiviera/img/<?= strtolower($tripSegment->transportMode) ?>.png" /> -->
										<span><?= strtolower($tripSegment->transportMode) ?></span>
									<?php } ?>	
								</li>
								<?php $listDivider = $tripSegment->type ?>
							<?php } ?>
							
							<?php
							$latitude =   $tripSegment->departurePoint->latitude;
							$longitude = $tripSegment->departurePoint->longitude;
							$poi =  str_replace("'", "", json_encode($tripSegment->poi)); 
							?>
							<input id="<?= $i ?>_poi" type="hidden" value='<?= $poi ?>' />
							
							<li style="font-size: 9pt; font-weight: lighter; padding:2px;">
								<a href="#" onclick="focusOn('<?= $i ?>', '<?= $latitude ?>', '<?= $longitude ?>', 'true'); <?= TARGET == "mobile" ? "$('#itineraire').trigger('collapse');" : "" ?>" data-icon="search" >
									<?php if(isset($tripSegment->distance)) { ?>
										<span>Distance: <?= $tripSegment->distance ?>m</span>
									<?php } else { ?>
										<span>Durée: <?= $tripSegment->duration ?>min</span>
									<?php } ?>
								</a>
								<p style="width: 90%;">
									<?= $tripSegment->comment ?>
								</p>
							</li>
									
							<?php $i++ ?>
						<?php } ?>
					</ul>
					<br />
				</div>	
				<?php } else if($this->handler->getError() == "1") {?>  	<!-- FROM GOOGLE -->
					<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "50" ?>%;">
						<h3>Feuille de route</h3>
						<!-- ITINERAIRE -->
						<ul data-role="listview" data-inset="true" data-theme="d" data-divider-theme="b">
						</ul>
					</div>
					<script type="text/javascript">setTimeout("calcRouteFromGoogle('<?= $_POST['Depart'] ?>','<?= $_POST['Arrivee'] ?>','<?= TARGET ?>')", 500);</script>
				<?php } ?>
			
			
			<!-- POIs -->
			<?php 
			// AROUND THE STARTING POINT
			if(isset($_POST['Départ'])){
				$geocode = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_POST['Départ']) . "&sensor=true"));
				if($geocode->status == "OK"){
					$longitude = $geocode->results[0]->geometry->location->lng;
					$latitude = $geocode->results[0]->geometry->location->lat;
						
					$request = new Request("POIRequestHandler", READ);
					$request->addArgument("application", APPLICATION_NAME);
					$request->addArgument("type", "mymed");
					$request->addArgument("longitude", $longitude);
					$request->addArgument("latitude", $latitude);
					$request->addArgument("radius", 1000); // AROUND 1 Kilometers
					$request->addArgument("accessToken", $_SESSION["accessToken"]);
						
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
// 					echo '<script type="text/javascript">alert(\'' . $responsejSon . '\');</script>';
					if($responseObject->status != 200) {
						// TODO
					}
				}
			}
			?>
			
		</div>
	<?php }
	
}
?>

