<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class AddView extends MyApplication {
	
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
		parent::__construct("Add");
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
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="3" />
				
				<!-- KEYWORD -->
				Name :<br />
				<input type="text" name="Name" value=""  data-inline="true"/><br />
				<?php $keyword = new MDataBean("Name", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- ENUM -->
				Category :<br />
				<select name="Category" data-theme="a">
				<?php foreach($this->channelCategory as $category) { ?>
					<option value="<?= $category ?>"><?= $category ?></option>
				<?php }?>
				</select>
				<?php $enum = new MDataBean("Category", null, ENUM); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($enum)); ?>">
				<br />
				
				<!-- TEXT -->
				Short Description :<br />
				<textarea name="Description" rows="" cols=""></textarea>
				<?php $text = new MDataBean("Description", null, TEXT); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($text)); ?>">
				<br />
				
				<br /><br />
				<a href="#" data-role="button"onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()">Send</a>	
			</form>
		</div>
	<?php }
	
}
?>