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
		
				
			<a id="Iti" data-role="actionsheet" data-sheet="Itin" data-icon="search" class="ui-btn-left" onclick=" setTime();">Itinéraire</a> 
			
			<div class="ui-btn-right">
				<select name="select-choice" id="select-choice" multiple="multiple" data-native-menu="false" >
					<option>Options</option>
					<option value="places">Lieux Public</option>
					<option value="restos">Restaurants</option>
					<option value="museums">Musées</option>
					<option value="events">Evenements</option>
				</select>
			</div>
			
			<h1>myRiviera</h1>

			<!-- <a href="#Option" data-role="button" data-rel="dialog" class="ui-btn-right" data-icon="gear">Options</a>  -->
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div data-role="content" style="padding: 0">
			
			<div id="myRivieraMap"></div>
			
			<!-- ITINERAIRE -->
			<?php if ($this->handler->getSuccess()) {
				// PRINT THE TRIP (kml format) FROM CITYWAY
				$kmlCityWay = $this->handler->getSuccess()->kml;
			} else if($this->handler->getError() == "1") { ?> 
				<!-- CITYWAY NOT AVAILABLE TRY WITH GOOGLE API -->
				<input id="start" type="hidden" value="<?= $_POST['Départ'] ?>">
				<input id="end" type="hidden" value="<?= $_POST['Arrivée'] ?>">
				<script type="text/javascript">console.log($('#start').val()); setTimeout("calcRoute()", 500);</script>
			<?php } ?>
			
			<!-- POIs -->
			<?php 
				// POIs FROM myMed
				$request = new Request("FindRequestHandler", READ);
				$request->addArgument("application", APPLICATION_NAME . "Admin");
				$request->addArgument("predicate", "keyword(myRivieraPOIs_myMed)");
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				if($responseObject->status == 200) {
					$resArray = json_decode($responseObject->data->results);
					$kmlCARF = $resArray[0]->data;
					?><textarea id="myMedPOIs" Style="display:none;"><?= $kmlCARF ?></textarea><?php
					echo '<script type="text/javascript">setTimeout("addMarkerFromMymedJsonFormat(\'myMedPOIs\')", 500);</script>';
				}
			
				// POIs FROM CARF
				$request = new Request("FindRequestHandler", READ);
				$request->addArgument("application", APPLICATION_NAME . "Admin");
				$request->addArgument("predicate", "keyword(myRivieraPOIs_carf)");
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				if($responseObject->status == 200) {
					$resArray = json_decode($responseObject->data->results);
					$kmlCARF = $resArray[0]->data;
					?><textarea id="CARFPOIs" Style="display:none;"><?= $kmlCARF ?></textarea><?php
					echo '<script type="text/javascript">setTimeout("addMarkerFromCARFJsonFormat(\'CARFPOIs\')", 500);</script>';
				}
			?>
			
		</div>
	<?php }
	
}
?>
