<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Details extends AbstractTemplate {
	
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
				<h2>Info</h2>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" style="text-align: left;">
			<?php
			$request = new Request("ProfileRequestHandler", READ);
			$request->addArgument("id",  $_GET['user']);
			$response = $request->send(); 
			// Check if there's not error
			$profile = json_decode($response);
			if(isset($profile->error)) { ?>
				<h2 style="color:red;"><?= $profile->error ?></h2>
			<?php } else { ?>
				<?php if($profile->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="180" height="150">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="180" height="150">
				<?php } ?>
				<br><br>
				Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
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
			    	} ?>
			    	<br />
			<?php } ?>
			<hr />
			<?php foreach(json_decode($this->handler->getSuccess()) as $details) { ?>
				<?= $details->key; ?> : <?= urldecode($details->value) ?>
				<br />
			<?php } ?>
		</div>
	<?php }
}
?>