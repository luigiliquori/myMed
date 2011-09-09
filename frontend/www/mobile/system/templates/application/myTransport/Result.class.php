<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Result extends AbstractTemplate {
	
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
	public function __construct(/*MyTransportHandler*/ $handler) {
		parent::__construct("myTransport", "myTransport");
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
			<a href="?application=myTransport" data-role="button" rel="external">Retour</a>
			<?php if(isset($_GET['publish'])) { ?>
				<h2>info</h2>
			<?php } else { ?>
				<h2>Résultats</h2>
			<?php } ?>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<?php if($this->handler->getSuccess() && isset($_GET['subscribe'])) { ?>
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
								<input type="hidden" name="application=myTransport" value="1" />
								<input type="hidden" name="getResult" value="1" />
								<input type="hidden" name="user" value="<?= $controller->user ?>" />
								<input type="hidden" name="predicate" value="<?= $controller->predicate ?>" />
							</form>
							<?php if($profile->profilePicture != "") { ?>
								<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="60" height="60">
							<?php } else { ?>
								<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="60" height="60">
							<?php } ?>
							<a rel="external" href="?application=myTransport&getDetails=1&user=<?= $controller->user ?>&predicate=<?= $controller->predicate ?>">
								<?= $profile->name ?>
							</a>
						<?php } ?>
						</li>
					<?php } ?>
				</ul>
			<?php } else if($this->handler->getSuccess() && isset($_GET['publish'])) { ?>
				<h2 style="color:green;">Message envoyé avec succès</h2>
				<a href="?application=myTransport" data-role="button" rel="external">Retour</a>
			<?php } else { ?>
				<h2 style="color:red;"><?= $this->handler->getError() ?></h2>
			<?php } ?>
		</div>
	<?php }
}
?>