<?php

require_once dirname(__FILE__).'/MyTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Find extends MyTemplate {
	
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
		parent::__construct("Rechercher");
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
				Key1
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/><br /><br />
				Key2 :
				<input type="text" name="start" value=""  data-inline="true"/><br />
				Key3 :
				<input type="text" name="end" value=""  data-inline="true"/><br />
				<a href="#" data-role="button"onclick="document.subscribeForm.submit()">Rechercher</a>	
			</form>
		</div>
	<?php }
	
}
?>