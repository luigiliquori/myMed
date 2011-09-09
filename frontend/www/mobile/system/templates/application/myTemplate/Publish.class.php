<?php

require_once dirname(__FILE__).'/MyTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Publish extends MyTemplate {
	
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
		parent::__construct("Publier");
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
		<!-- <form  action="http://mymed2.sophia.inria.fr:8080/mymed_backend/PubSubRequestHandler" method="post" name="publishForm" id="publishForm"> -->
			<form  action="#" method="get" name="publishForm" id="publishForm">
				<input type="hidden" name="application" value="myTemplate" />
				<input type="hidden" name="publish" value="1" />
				Key1 :
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/><br /><br />
				Key2 :
				<input type="text" name="start" value=""  data-inline="true"/><br />
				Key3 :
				<input type="text" name="end" value=""  data-inline="true"/><br />
				Value1 :
				<select name="type" data-theme="a">
					<option value="Option1">Option1</option>
					<option value="Option2">Option2</option>
					<option value="Option3">Option3</option>
				</select>
				<br />
				Value2 :
				<textarea name="comment"></textarea><br />
				<a href="#" data-role="button" onclick="document.publishForm.submit()">Publier</a>
			</form>
		</div>
	<?php }
	
}
?>