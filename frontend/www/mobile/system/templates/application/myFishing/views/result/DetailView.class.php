<?php
require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';
require_once 'system/request/Reputation.class.php';

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
			<!-- PROFILE -->
			<?php
			$request = new Request("ProfileRequestHandler", READ);
			$request->addArgument("id",  $_POST['user']);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) { ?>
				<h2 style="color:red;"><?= $profile->error ?></h2>
			<?php } else { 
				$profile = json_decode($responseObject->data->user);
				if($profile->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="180">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="180">
				<?php } ?>
				<br><br>
				Prenom: <?= $profile->firstName ?><br />
				Nom: <?= $profile->lastName ?><br />
				Date de naissance: <?= $profile->birthday ?><br />
				eMail: <?= $profile->email ?><br />
				Reputation:
				<?php 
					$reputation = new Reputation();
					$value = $reputation->getReputation(APPLICATION_NAME, $profile->id, $_SESSION['user']->id);
					$percent = ($value * 100);
					echo $percent . "%"; 
				?><br />
				<?php 
		    	$j=0;
		    	while($j<=$percent){ ?>
		    		<img alt="star" src="img/star.png" width="20" />
		    		<?php 
		    		$j+=25;
		    	}
		    	while($j<=100){ ?>
		    		<img alt="star" src="img/starGray.png" width="20" />		
		    		<?php 
		    		$j+=25;
		    	} ?>
		    	<br />
			<?php } ?>
			
			<hr />
			
			<!-- VALUES -->
			<?php foreach(json_decode($this->handler->getSuccess()) as $details) { ?>
				<?php if ($details->key == "picture") { ?>
					<?= $details->key; ?> : 
					<a href="<?= urldecode($details->value) ?>" target="blank">
						<img alt="no picture" src="<?= urldecode($details->value) ?>" width="180" />
					</a>
				<?php } else { ?>
					<?= $details->key; ?> : <?= urldecode($details->value) ?>
				<?php } ?>
				<br />
			<?php } ?>
			
			<?php if($_SESSION['user']->id != $profile->id) { ?>
				<!-- REPUTATION -->
				<form action="#" method="post" name="increaseRepForm" id="increaseRepForm">
		    		<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="startInteraction" />
		    		<input type="hidden" name="producer" value="<?= $profile->id ?>">
		    		<input type="hidden" name="consumer" value="<?= $_SESSION['user']->id ?>" />
		    		<input type="hidden" name="start" value="<?= time(); ?>" />
		    		<input type="hidden" name="end" value="<?= time(); ?>" />
		    		<input type="hidden" name="feedback" value="1" />
		    		<input type="hidden" name="predicate" value="<?= $_POST['predicate'] ?>" />
		    	</form>
		    	<form action="#" method="post" name="decreaseRepForm" id="decreaseRepForm">
		    		<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="startInteraction" />
		    		<input type="hidden" name="producer" value="<?= $profile->id ?>">
		    		<input type="hidden" name="consumer" value="<?= $_SESSION['user']->id ?>" />
		    		<input type="hidden" name="start" value="<?= time(); ?>" />
		    		<input type="hidden" name="end" value="<?= time(); ?>" />
		    		<input type="hidden" name="feedback" value="0" />
		    		<input type="hidden" name="predicate" value="<?= $_POST['predicate'] ?>" />
		    	</form>
		    	<a data-role="button" data-inline="true" data-theme="a" onclick="document.decreaseRepForm.submit()">-1</a>
		    	<a data-role="button" data-inline="true" data-theme="a" onclick="document.increaseRepForm.submit()">+1</a>
	    	<?php } ?>
		</div>
	<?php }
}
?>
