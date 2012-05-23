<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';
require_once '../../lib/dasp/request/Reputation.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class SubscribeView extends MainView {
	
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
	public function __construct($handler) {
		$this->handler = $handler;
		parent::__construct("SubscribeView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	
	/**
	* 
	*/
	private /*String*/ function getSubscribeContent() { ?>

		<form action="" method="post" name="getSubscribeForm1">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Lingua", "italiano", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm2">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Lingua", "francese", KEYWORD))); ?>" type="hidden" >
		</form>
		

		<form action="" method="post" name="getSubscribeForm3">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("commentGroup", APPLICATION_NAME, KEYWORD))); ?>" type="hidden" >
		</form>
		<div style="width:700px;margin-left:auto; margin-right:auto;">
			<a type="button" data-inline="true" data-theme="e" href="#" onclick="$(this).parent().addClass('ui-disabled');document.getSubscribeForm1.submit();">Sottoscrivere testi (it)</a>
			<a type="button" data-inline="true" data-theme="e" href="#" onclick="$(this).parent().addClass('ui-disabled');document.getSubscribeForm2.submit();">Sottoscrivere testi (fr)</a>
			<a type="button" data-inline="true" data-theme="e" href="#" onclick="$(this).parent().addClass('ui-disabled');document.getSubscribeForm3.submit();" >Sottoscrivere commenti</a>
		</div>
		
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" style="padding: 10px;" data-theme="c">';
			$this->getSubscribeContent();
		echo '</div>';
	}
}
?>
