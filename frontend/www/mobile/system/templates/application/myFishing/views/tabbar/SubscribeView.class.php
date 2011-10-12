<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class SubscribeView extends MyApplication {
	
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
		parent::__construct("Subscribe");
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
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>SubscribeForm" id="<?= APPLICATION_NAME ?>SubscribeForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="subscribe" />
				<input type="hidden" name="numberOfOntology" value="3" />

				<!-- ENUM for Fish Specie -->
				Kind of Fish:<br />
				<select name="FishSpecie" data-theme="b">
					<option value="Trout">Trout</option>
					<option value="Grayling">Grayling</option>
					<option value="Salmon">Salmon</option>
					<option value="Dorade">Dorade</option>					
				</select>
				<?php $enum = new MDataBean("FishSpecie", null, ENUM); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($enum)); ?>">
				<br />
				
				<!-- GPS -->
				Location :<br />
				<input type="text" name="Location" value=""  data-inline="true" data-theme="c"/>
				<?php $gps = new MDataBean("Location", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- DATE -->
				Catch Date:<br />
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="c"/>
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
				<br />

				<br /><br />
				<a href="#" data-role="button"onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()">Subscribe</a>	
			</form>
		</div>
	<?php }
	
}
?>