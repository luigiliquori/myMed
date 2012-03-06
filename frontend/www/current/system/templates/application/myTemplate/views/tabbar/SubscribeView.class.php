<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

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
				<input type="hidden" name="numberOfOntology" value="4" />
				
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
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="a"/>
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($date)); ?>">

				<br /><br />
				<a href="#" data-role="button"onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()">Subscribe</a>	
			</form>
		</div>
	<?php }
	
}
?>