<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';
require_once dirname(__FILE__).'/handler/MyTransportHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class MyTransport extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $activeFooter;
	private /*MyTransportHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		parent::__construct($id, "myTransport");
		$this->activeFooter = $id;
		$this->handler = new MyTransportHandler();
		$this->handler->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- NOTIFICATION -->
		<?php if($this->handler->getError()) { ?>
			<div data-role="header" data-theme="a">
				<a href="?application=myTransport" data-role="button" rel="external">Close</a>
				<h2>No Result</h2>
			</div>
		<?php } else { ?>
			<div data-role="header" data-theme="b">
				<a href="?application=0" rel="external" data-role="button" data-theme="r">Fermer</a>
				<h1><?= $this->title ?></h1>
			</div>
		<?php }?>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER_PERSITENT-->
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
				<li><a href="#Carte" <?= $this->activeFooter == "Carte" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Carte</a></li>
				<li><a href="#Publier" data-back="true" <?= $this->activeFooter == "Publier" ? 'class="ui-btn-active ui-state-persist"' : ''; ?> >Publier</a></li>
				<li><a href="#Rechercher" <?= $this->activeFooter == "Rechercher" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Rechercher</a></li>
				</ul>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="b">
			<?php if($this->handler->getSuccess() && isset($_GET['subscribe'])) { ?>
				<div data-role="header" data-theme="a">
					<a href="?application=myTransport" data-role="button" rel="external">Close</a>
					<h1>Result:</h1>
				</div>
				<div class="content">
					<?php
					$request = new Request("ProfileRequestHandler", READ);
					$request->addArgument("id", $this->handler->getSuccess());
					$response = $request->send();
					
					// Check if there's not error
					$check = json_decode($response);
					if(isset($check->error)) { ?>
						<h2>0 Result</h2>
					<?php } else { ?>
						<div style="text-align: left;">
							<?php if($check->profilePicture != "") { ?>
								<img alt="thumbnail" src="<?= $check->profilePicture ?>" width="180" height="150">
							<?php } else { ?>
								<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="180" height="150">
							<?php } ?>
							<br><br>
							Prenom: <?= $check->firstName ?><br />
							Nom: <?= $check->lastName ?><br />
							Date de naissance: <?= $check->birthday ?><br />
							eMail: <?= $check->email ?><br />
							Reputation: 
							 <?php 
						    	$rand = rand(0, 4);
						    	$j=0;
						    	while($j<=$rand){ ?>
						    		<img alt="star" src="img/star.png" width="20" />
						    		<?php 
						    		$j++;
						    	}
						    	while($j<=4){ ?>
						    		<img alt="star" src="img/starGray.png" width="20" />		
						    		<?php 
						    		$j++;
						    	}
						    ?>
						</div>
					<?php } ?>
				</div>
			<?php } else { 
				if($this->handler->getSuccess() && isset($_GET['publish'])) { ?>
					<div data-role="header" data-theme="a">
						<a href="?application=myTransport" data-role="button" rel="external">Close</a>
						<h1><?= $this->handler->getSuccess() ?></h1>
					</div>
				<?php } else { ?>
					<?php  $this->getHeader(); ?>
				<?php } ?>
				<?php $this->getContent(); ?>
				<?php $this->getFooter(); ?>
			<?php } ?>
		</div>
	<?php }
}
?>