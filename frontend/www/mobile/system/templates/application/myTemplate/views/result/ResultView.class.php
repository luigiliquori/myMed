<?php
require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class ResultView extends MyApplication {
	
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
			<?php if(isset($_GET['publish'])) { ?>
				<h2>info</h2>
			<?php } else { ?>
				<h2>Résultats</h2>
			<?php } ?>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<?php if($this->handler->getSuccess() && $_POST['method'] == "find") { ?>
				<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a" >
				<?php foreach(json_decode($this->handler->getSuccess()) as $controller) { ?>
					<li>
						<?php
						$request = new Request("ProfileRequestHandler", READ);
						$request->addArgument("id", $controller->user);
						$response = $request->send();
						
						// Check if there's not error
						$profile = json_decode($response);
						if(isset($profile->error)) { ?>
							<h2 style="color:red;"><?= $profile->error ?></h2>
						<?php } else {?>
							<!-- RESULT DETAILS -->
							<form action="#" method="post" name="getDetailForm" id="getDetailForm">
								<input type="hidden" name="application=<?= APPLICATION_NAME ?>" value="1" />
								<input type="hidden" name="getResult" value="1" />
								<input type="hidden" name="user" value="<?= $controller->user ?>" />
								<input type="hidden" name="predicate" value="<?= $controller->predicate ?>" />
							</form>
							<?php if($profile->profilePicture != "") { ?>
								<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="60" height="60">
							<?php } else { ?>
								<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="60" height="60">
							<?php } ?>
							<a rel="external" href="?application=<?= APPLICATION_NAME ?>&getDetails=1&user=<?= $controller->user ?>&predicate=<?= $controller->predicate ?>">
								<?= $profile->name ?>
							</a>
						<?php } ?>
						</li>
					<?php } ?>
				</ul>
			<?php } else if($this->handler->getSuccess() && ($_POST['method'] == "publish" || $_POST['method'] == "subscribe")) { ?>
				<h2 style="color:green;">Message envoyé avec succès</h2>
				<a href="?application=<?= APPLICATION_NAME ?>" data-role="button" rel="external">Retour</a>
			<?php } else { ?>
				<h2 style="color:red;"><?= $this->handler->getError() ?></h2>
			<?php } ?>
		</div>
	<?php }
}
?>