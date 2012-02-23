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
				<div class="grup" data-role="collapsible" data-mini="true" data-theme="b" data-content-theme="d" data-collapsed=<?php $i?"false":"true" ?> onclick="$('.grup').not(this).trigger('collapse');">
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
					</div>
					<div style="font-size: 9pt; font-weight: lighter; padding:2px;">
						<span>Durée: <?= $tripSegment->duration ?>min</span>
					</div>
					<?php $listDivider = null;?>
					<?php continue; ?>
				<?php } else { ?>
					<span><?= strtolower($tripSegment->transportMode) ?></span>
					<?php $titre = strtolower($tripSegment->transportMode) ?>
					<?php $icon = "system/templates/application/myRiviera/img/" . strtolower($tripSegment->transportMode) . ".png" ?>
				<?php } ?>	
				</div>
				<?php 
				$listDivider = $tripSegment->type;
			}
			  
			$latitude =   $tripSegment->departurePoint->latitude;
			$longitude = $tripSegment->departurePoint->longitude;
			if(isset($tripSegment->poi)){
				$poi =  str_replace("'", "", json_encode($tripSegment->poi)); 
			}
			?>
			
			<input id="poi_<?= $i ?>" type="hidden" value='<?= $poi ?>' />
			<div style="font-size: 9pt; font-weight: lighter; padding:2px;">
				
				<!-- FOCUS ON -->
				<a href="#" onclick="
				focusOnPosition('<?= $latitude ?>', '<?= $longitude ?>');
				updatePOIs('<?= $latitude ?>', '<?= $longitude ?>', '<?= $icon ?>', '<?= $titre ?>', '<?= $i ?>');
				<?= TARGET == "mobile" ? "$('#itineraire').trigger('collapse');" : "" ?>"
				data-icon="search" >
					<?php if(isset($tripSegment->distance)) { ?>
					<span>Distance: <?= $tripSegment->distance ?>m</span>
					<?php } else { ?>
					<span>Durée: <?= $tripSegment->duration ?>min</span>
					<?php } ?>
				</a>
				<p id="poicomment_<?= $i ?>" style="width: 90%;"><?= $tripSegment->comment ?></p>
			</div>
			
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
			
			<script type="text/javascript">
				var mobile = '<?php echo TARGET ?>';
			</script>
			
			<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "35" ?>%;">
				<h3>Feuille de route</h3>
				<div id="itineraireContent" data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true"></div>
			</div>

			<?php if ($this->handler->getSuccess()) { ?>			
				
				<!-- ITINERARY -->
				<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "35" ?>%;">
					<h3>Feuille de route <?= $this->handler->getSuccess()->itineraire->type ?></h3>
					<div id="itineraireContent" data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true"></div>
				</div>	
				
				<!-- Calcul Route -->
				<script type="text/javascript"> 
					var result = <?php echo $this->handler->getSuccess()->itineraire->value ?>;
					var resulttype = <?php echo $this->handler->getSuccess()->itineraire->type ?>;
					setTimeout("calcRoute('<?= $_POST['Depart'] ?>','<?= $_POST['Arrivee'] ?>','<?= TARGET ?>')", 500);
				</script>
				
				<!-- Display The trip on the map -->
				<?php 
				$start = str_replace("\"", "", str_replace("'", "", $_POST['Depart']));
				$end = str_replace("\"", "", str_replace("'", "", $_POST['Arrivee']));
				?>
				<script type="text/javascript">setTimeout("myRivieraShowTrip('<?= $start ?>', '<?= $end ?>')", 500);</script>
				
			<?php } ?>
			
		</div>
	<?php }
	
}
?>