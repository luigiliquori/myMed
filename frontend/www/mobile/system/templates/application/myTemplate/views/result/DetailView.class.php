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
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() {
	}
	
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
				Prenom: <?= $profile->firstName ?><br />
				Nom: <?= $profile->lastName ?><br />
				Date de naissance: <?= $profile->birthday ?><br />
				eMail: <?= $profile->email ?><br />
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
			    	<a href="?admin" rel="external" data-role="button" data-inline="true" data-theme="a">+1</a>
			    	<a href="?admin" rel="external" data-role="button" data-inline="true" data-theme="a">-1</a>
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