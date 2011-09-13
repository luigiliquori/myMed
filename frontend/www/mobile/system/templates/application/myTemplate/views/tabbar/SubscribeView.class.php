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
			<form  action="#" method="get" name="subscribeForm" id="subscribeForm">
				<input type="hidden" name="application" value="myTemplate" />
				<input type="hidden" name="subscribe" value="1" />
				Ontology 1 (Type: KEYWORD) :<br />
				<input type="text" name="start" value=""  data-inline="true"/><br />
				Ontology 2 (Type: GPS) :<br />
				<input type="text" name="start" value=""  data-inline="true"/><br />
				Ontology 3 (Type: ENUM) :<br />
				<select name="type" data-theme="a">
					<option value="Option1">Option1</option>
					<option value="Option2">Option2</option>
					<option value="Option3">Option3</option>
				</select><br />
				Ontology 4 (Type: DATE) :<br />
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/>
				<br /><br />
				<a href="#" data-role="button"onclick="document.subscribeForm.submit()">Subscribe</a>	
			</form>
		</div>
	<?php }
	
}
?>