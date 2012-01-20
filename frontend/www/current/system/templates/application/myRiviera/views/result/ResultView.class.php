<?php
require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class ResultView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*MyTransportHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct(APPLICATION_NAME, APPLICATION_NAME);
		$this->handler = $handler;
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="a">
			<a href="?application=<?= APPLICATION_NAME ?>" data-role="button" rel="external">Retour</a>
			<h2>Résultats</h2>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			
			<div id="map_canvas" style="width: 100%; height: 280px; border: thin gray solid; text-align: center; background-color: gray;">
				<br><br>
				Loading map...
			</div>
			<br>
			
			<?php if ($this->handler->getSuccess()) {
				
				// PRINT THE TRIP ON THE MAP
				$kml = $this->handler->getSuccess()->kml;
				$originLatitude = $this->handler->getSuccess()->itineraire->ItineraryObj->originPoint->latitude;
				$originLongitude = $this->handler->getSuccess()->itineraire->ItineraryObj->originPoint->longitude;
				echo '<script type="text/javascript">showMap(\'' . $kml . '\',\'' . $originLatitude . '\',\'' . $originLongitude . '\');</script>';
				
				// PRINT THE ITINEARY
				$listDivider = null;?>
				<ul data-role="listview" data-theme="c" data-dividertheme="a" data-inset="false">
				<?php $i=0 ?>
				<?php foreach($this->handler->getSuccess()->itineraire->ItineraryObj->tripSegments->tripSegment as $tripSegment) { ?>

					<?php if($listDivider == null || $listDivider != $tripSegment->type) { ?>
						<li data-role="list-divider"><?php 
							if($tripSegment->type == "WALK") { ?>
								<img alt="Marche" src="system/templates/application/myRiviera/img/<?= strtolower($tripSegment->type) ?>.png" />
								<span Style="position: relative; left: 25px;">Marche</span>
							<?php } else if($tripSegment->type == "CONNECTION") { ?>
								<span>Connection</span>
							<?php } else  { ?>
								<img alt="Marche" src="system/templates/application/myRiviera/img/<?= strtolower($tripSegment->transportMode) ?>.png" />
								<span Style="position: relative; left: 25px;"><?= strtolower($tripSegment->transportMode) ?></span>
							<?php } ?>	
						</li>
						<?php $listDivider = $tripSegment->type ?>
					<?php } ?>
					
					<li>
						<div class="ui-btn-text">
							<?php
								$latitude =   $tripSegment->departurePoint->latitude;
								$longitude = $tripSegment->departurePoint->longitude;
								$poi =  str_replace("'", "", json_encode($tripSegment->poi)); 
							?>
							<input id="<?= $i ?>_latitude" type="hidden" value='<?= $latitude ?>' />
							<input id="<?= $i ?>_longitude" type="hidden" value='<?= $longitude ?>' />
							<input id="<?= $i ?>_poi" type="hidden" value='<?= $poi ?>' />
							<a href="#" onclick="focusOn('<?= $i ?>');" style="position: relative; left: -13px;">
								<?php if(isset($tripSegment->distance)) { ?>
									<h3>Distance: <?= $tripSegment->distance ?>m</h3>
								<?php } else { ?>
									<h3>Durée: <?= $tripSegment->duration ?>min</h3>
								<?php } ?>
								<p class="ui-li-desc"><?= $tripSegment->comment ?></p>
							</a>
						</div>
					</li>
					<?php $i++ ?>
				<?php } ?>
				</ul>
				
			<?php } else { ?>
				<h2 style="color:red;"><?= $this->handler->getError() ?></h2>
			<?php } ?>
			
		</div>
	<?php }
}
?>