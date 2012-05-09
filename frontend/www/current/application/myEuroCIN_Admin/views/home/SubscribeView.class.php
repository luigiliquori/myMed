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
		$this->dataBean1 = new MDataBean("Lingua", "italiano", KEYWORD);
		$this->dataBean2 = new MDataBean("Lingua", "francese", KEYWORD);
		parent::__construct("SubscribeView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	
	/**
	* 
	*/
	private /*String*/ function getSubscribeContent() { ?>
		<br/><br/>
		<form action="" method="post" name="getSubscribeForm1">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			
			<input name="ontology0" value="<?= urlencode(json_encode($this->dataBean1)); ?>" type="hidden" >
		</form>
		<a type="button" data-theme="g" href="#" onclick="document.getSubscribeForm1.submit()">Sottoscrivere ai testi in italiano</a>
		
		<form action="" method="post" name="getSubscribeForm2">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode($this->dataBean2)); ?>" type="hidden" >
		</form>
		<a type="button" data-theme="g" href="#" onclick="document.getSubscribeForm2.submit()">Sottoscrivere ai testi in francese</a>
		
		<br/><br/>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
			$this->getSubscribeContent();
		echo '</div>';
	}
}
?>
