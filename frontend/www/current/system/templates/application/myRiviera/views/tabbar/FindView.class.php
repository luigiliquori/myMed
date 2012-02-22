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
			<a id="Iti" data-role="actionsheet" data-sheet="Itin" data-icon="search" data-iconpos="right" class="ui-btn-left">Rechercher</a> 
			<a href="#Option" data-role="button" class="ui-btn-right" data-icon="gear">Options</a>
		</div>
	<?php }
	
	/**
	 * Print the itinerary
	 * @param jSon $itinerary
	 */
	public /*void*/ function printItinerary($itinerary) { ?>
		
		<!-- ITINERAIRE -->
		<?php 
		$listDivider = null;
		$i=0;
		foreach($itinerary as $tripSegment) {
			if($listDivider == null || $listDivider != $tripSegment->type) {
				$icon = null ?>
				<li data-role="list-divider">
				<?php if($tripSegment->type == "WALK") { ?>
					<span>Marche</span>
					<?php 
					$titre = "Marche";
					$icon = "system/templates/application/myRiviera/img/" . strtolower($tripSegment->type) . ".png";
					?>
				<?php } else if($tripSegment->type == "CONNECTION") { ?>
					<span>Connection</span>
				<?php } else if($tripSegment->type == "WAIT") { ?>
					<span>Attendre</span>
					</li>
					<li style="font-size: 9pt; font-weight: lighter; padding:2px;">
						<span>Durée: <?= $tripSegment->duration ?>min</span>
					</li>
					<?php $listDivider = null;?>
					<?php continue; ?>
				<?php } else { ?>
					<span><?= strtolower($tripSegment->transportMode) ?></span>
					<?php $titre = strtolower($tripSegment->transportMode) ?>
					<?php $icon = "system/templates/application/myRiviera/img/" . strtolower($tripSegment->transportMode) . ".png" ?>
				<?php } ?>	
				</li>
				<?php 
				$listDivider = $tripSegment->type;
			}
			  
			$latitude =   $tripSegment->departurePoint->latitude;
			$longitude = $tripSegment->departurePoint->longitude;
			if(isset($tripSegment->poi)){
				$poi =  str_replace("'", "", json_encode($tripSegment->poi)); 
			}
			?>
			
			<input id="<?= $i ?>_poi" type="hidden" value='<?= $poi ?>' />
			<li style="font-size: 9pt; font-weight: lighter; padding:2px;">
				
				<!-- FOCUS ON -->
				<a href="#" onclick="
				focusOn('<?= $i ?>', '<?= $latitude ?>', '<?= $longitude ?>');
				addPOI('<?= $latitude ?>', '<?= $longitude ?>', '<?= $icon ?>', '<?= $titre ?>', '<?= urlencode($tripSegment->comment) ?>', true);
				<?= TARGET == "mobile" ? "$('#itineraire').trigger('collapse');" : "" ?>"
				data-icon="search" >
					<?php if(isset($tripSegment->distance)) { ?>
					<span>Distance: <?= $tripSegment->distance ?>m</span>
					<?php } else { ?>
					<span>Durée: <?= $tripSegment->duration ?>min</span>
					<?php } ?>
				</a>
				<p style="width: 90%;"><?= $tripSegment->comment ?></p>
			</li>
			
			<?php 
			$i++;
		}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div data-role="content" style="padding: 0px;">
			
			<!-- MAP -->
			<div id="myRivieraMap"></div>

			<?php if ($this->handler->getSuccess()) { ?>			
				
				<!-- FROM CITYWAY -->
				<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "35" ?>%;">
					<h3>Feuille de route - Source <?= $this->handler->getSuccess()->itineraire->type ?></h3>
					<ul data-role="listview" data-inset="true" data-theme="d" data-divider-theme="b">
						<?php 
							// TODO REMOVE THIS TEST, THE GOOGLE TRIP SHOULD BE DONE BY THE APPLICATION HANDLER (same way as cityway)
							if($this->handler->getSuccess()->itineraire->type == "Google") {
								?><script type="text/javascript">setTimeout("calcRouteFromGoogle('<?= $_POST['Depart'] ?>','<?= $_POST['Arrivee'] ?>','<?= TARGET ?>')", 500);</script><?php
							} else {
								$this->printItinerary($this->handler->getSuccess()->itineraire->value);
							}
						?>
					</ul>
					<br />
				</div>	
				
				<!-- Display The trip on the map (start9end+fitbounds) -->
				<?php 
				$start = str_replace("\"", "", str_replace("'", "", $_POST['Depart']));
				$end = str_replace("\"", "", str_replace("'", "", $_POST['Arrivee']));
				?>
				<script type="text/javascript">setTimeout("showTrip('<?= $start ?>', '<?= $end ?>')", 500);</script>
				
			<?php } ?>
			
		</div>
	<?php }
	
}
?>

