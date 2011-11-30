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
				
				if(isset($_SESSION['KML'])) { // update the map
					$kml = $_SESSION['KML'];
					$originLatitude = $this->handler->getSuccess()->ItineraryObj->originPoint->latitude;
					$originLongitude = $this->handler->getSuccess()->ItineraryObj->originPoint->longitude;
					echo '<script type="text/javascript">setTimeout("updateMapWithKML(\'' . $kml . '\',\'' . $originLatitude . '\',\'' . $originLongitude . '\')", 1500);</script>';
				}
				
				$listDivider = null;?>
				<ul data-role="listview" data-theme="c" data-dividertheme="a" >
				<?php foreach($this->handler->getSuccess()->ItineraryObj->tripSegments->tripSegment as $tripSegment) { ?>
					<?php if($listDivider == null || $listDivider != $tripSegment->type) { ?>
						<li data-role="list-divider"><?= $tripSegment->type ?></li>
						<?php $listDivider = $tripSegment->type ?>
					<?php } ?>
					<li><?= $tripSegment->comment ?></li>
				<?php } ?>
				</ul>
				
			<?php } else { ?>
				<h2 style="color:red;"><?= $this->handler->getError() ?></h2>
			<?php } ?>
			
		</div>
	<?php }
}
?>