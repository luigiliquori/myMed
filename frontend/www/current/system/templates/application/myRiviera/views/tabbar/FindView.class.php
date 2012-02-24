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
			<div id="next-prev">
				<a id="prev-step" data-role="button" data-theme="b" data-iconpos="notext" data-icon="arrow-l" data-inline="true" onclick=""></a>
				<a id="next-step" data-role="button" data-theme="b" data-iconpos="notext" data-icon="arrow-r" data-inline="true" onclick=""></a>
			</div>
			
			<?php if ($this->handler->getSuccess()) { ?>			
				
				<!-- ITINERARY -->
				<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "35" ?>%;">
					<h3>Feuille de route <?= $this->handler->getSuccess()->itineraire->type ?></h3>
					<div id="itineraireContent" data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true"></div>
				</div>
				<?php 
				$start = str_replace("\"", "", str_replace("'", "", $_POST['Depart']));
				$end = str_replace("\"", "", str_replace("'", "", $_POST['Arrivee']));
				?>
				
				<!-- Calcul Route -->
				<script type="text/javascript"> 
					var result = <?php echo $this->handler->getSuccess()->itineraire->value ?>;
					setTimeout("calcRoute('<?= $start ?>','<?= $end ?>','<?= TARGET ?>')", 500);
				</script>
				
				<!-- Display The trip on the map -->
				<script type="text/javascript">setTimeout("myRivieraShowTrip('<?= $start ?>', '<?= $end ?>')", 500);</script>
				
			<?php } ?>
			
		</div>
	<?php }
	
}
?>