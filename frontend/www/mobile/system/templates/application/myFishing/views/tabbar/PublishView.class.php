<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/beans/MDataBean.class.php';

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
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Publish");
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
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="5" />
				
				<!-- ENUM for Fish Specie -->
				Kind of Fish:<br />
				<select name="FishSpecie" data-theme="a">
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
				<input type="text" name="Location" value=""  data-inline="true"/>
				<?php $gps = new MDataBean("Location", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- DATE -->
				Catch Date:<br />
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/>
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
				<br />
								
				<!-- PICTURE -->
				Picture (URL):<br />
				<input type="text" name="picture" value=""  data-inline="true"/><br />
				<?php $keyword = new MDataBean("picture", null, PICTURE); ?>
				<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- Comment -->
				Public Notes :<br />
				<textarea name="comment" rows="" cols=""></textarea>
				<?php $text = new MDataBean("comment", null, TEXT); ?>
				<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($text)); ?>">
				<br />
	
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()">Publish</a>
			</form>
		</div>
	<?php }
	
}
?>