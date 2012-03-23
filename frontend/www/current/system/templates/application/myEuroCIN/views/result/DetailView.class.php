<?php
require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';
require_once 'lib/dasp/request/Reputation.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class DetailView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*MyTransportHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct(APPLICATION_NAME, APPLICATION_NAME);
		$this->handler = $handler;
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="a">
			<a href="?application=<?= APPLICATION_NAME ?>" data-role="button" rel="external">Retour</a>
				<h2>Info</h2>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" style="text-align: left; color: black;">
			<?php
			$request = new Request("ProfileRequestHandler", READ);
			$request->addArgument("id",  $_POST['user']);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if ($responseObject->status != 200) {
				echo '<h2 style="color:red;">' . $profile->error . '</h2>';
			} else { 
				foreach(json_decode($this->handler->getSuccess()) as $details) {
					if ($details->key == "data") {
						echo "<h3>" . urldecode($details->value) . "</h3>";
					} else if ($details->key == "text") {
						echo "<div>" . urldecode($details->value) . "</div>";
					}
				 }
			} ?>
		</div>
	<?php }
}
?>
