<?php

require_once 'system/templates/application/myTemplate/MyTemplate.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class SubscribeView extends MyTemplate {
	
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
		<div class="content" data-role="content">
			<form  action="#" method="post" name="myTemplateSubscribeForm" id="myTemplateSubscribeForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="myTemplate" />
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
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/>
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($date)); ?>">

				<br /><br />
				<a href="#" data-role="button"onclick="document.myTemplateSubscribeForm.submit()">Subscribe</a>	
			</form>
		</div>
	<?php }
	
}
?>