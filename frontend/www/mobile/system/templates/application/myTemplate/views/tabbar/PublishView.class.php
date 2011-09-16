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
				
				<!-- KEYWORD -->
				Ontology 0 (Type: KEYWORD) :<br />
				<input type="text" name="keyword" value=""  data-inline="true"/><br />
				<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- GPS -->
				Ontology 1 (Type: GPS) :<br />
				<input type="text" name="gps" value=""  data-inline="true"/>
				<?php $gps = new MDataBean("gps", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- ENUM -->
				Ontology 2 (Type: ENUM) :<br />
				<select name="enum" data-theme="a">
					<option value="Option1">Option1</option>
					<option value="Option2">Option2</option>
					<option value="Option3">Option3</option>
				</select>
				<?php $enum = new MDataBean("enum", null, ENUM); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($enum)); ?>">
				<br />
				
				<!-- DATE -->
				Ontology 3 (Type: DATE) :<br />
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/>
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($date)); ?>">
				<br />
				
				<!-- TEXT -->
				Ontology 4 (Type: TEXT) :<br />
				<textarea name="text" rows="" cols=""></textarea>
				<?php $text = new MDataBean("text", null, TEXT); ?>
				<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($text)); ?>">
				<br />
				
				<!-- PICTURE -->
				Ontology 5 (Type: PICTURE) :<br />
				<input type="file" disabled="disabled" />
				<br />
				
				<!-- VIDEO -->
				Ontology 6 (Type: VIDEO) :<br />
				<input type="file" disabled="disabled" /><br />
				
				<!-- AUDIO -->
				Ontology 7 (Type: AUDIO) :<br />
				<input type="file" disabled="disabled" /><br /><br />
				
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()">Publish</a>
			</form>
		</div>
	<?php }
	
}
?>