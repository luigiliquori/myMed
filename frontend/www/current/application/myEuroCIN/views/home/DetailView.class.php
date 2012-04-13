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
class DetailView extends MainView {
	
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
		parent::__construct("DetailView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
		
			<a href="#ResultView" data-role="button" data-direction="reverse" data-inline="true">back</a><br /><br /> <?php 
			
			if(isset($_POST['method']) && $_POST['method'] == "getDetail" && $this->handler->getSuccess()) {
				$details = json_decode($this->handler->getSuccess());
				$_SESSION[APPLICATION_NAME]['details'] = $details;
				$user = $_POST['user'];
				$_SESSION[APPLICATION_NAME]['details']['user'] = $user;
			} else {
				$details = $_SESSION[APPLICATION_NAME]['details'];
				$user = $_SESSION[APPLICATION_NAME]['details']['user'];
			}
			?>
			<!-- TEXT -->
			<?php foreach($details as $detail) {
				if(isset($detail->key) && isset($detail->value)) {
					if($detail->key == "data") { 
						$title = urldecode($detail->value);
					}
					if($detail->key == "text") {
						$text = urldecode($detail->value);
					}
				}
			} ?>
			<h1><?= $title ?></h1>
			<p><?= $text ?></p>
			
	    	<!-- AUTHOR -->
	    	<?php
	    	$request = new Request("ProfileRequestHandler", READ);
	    	$request->addArgument("id",  $user);
	    	$responsejSon = $request->send();
	    	$responseObject = json_decode($responsejSon);
	    	$profile = json_decode($responseObject->data->user);
	    	?>
	    	<?php if($profile->profilePicture != "") { ?>
	    		<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="30" height="30" style="position: absolute;">
	    	<?php } else { ?>
	    		<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="30" height="30" style="position: absolute;">
	    	<?php } ?>
		    <div style="position: relative; top: 3px; left:35px; height: 30px;"><?= $profile->firstName ?> <?= $profile->lastName ?></div>
	    	
	    	<hr />
	    	
	    	<!-- COMMENT -->
	    	<div data-role="collapsible"  data-content-theme="d">
	    		<h3>Comment</h3>
		    	<?php
		    	$request = new Request("FindRequestHandler", READ);
		    	$request->addArgument("application", APPLICATION_NAME);
		    	$request->addArgument("predicate", "commentOn" . $title);
		    	$responsejSon = $request->send();
		    	$responseObject = json_decode($responsejSon);
		    	if($responseObject->status == 200) {
			    	foreach(json_decode($responseObject->data->results) as $controller) { ?>
			    		<p><?= $controller->data ?></p>
			    		<p><?= $controller->publisherName ?></p>
			    		<p><?= $controller->begin ?></p>
			    		<br /><br />
			    	<?php } ?>
				<?php } else { ?>
					<p><?= $responseObject->description ?></p>
				<?php } ?>
				<form  action="#DetailView" method="post" name="CommentPublishForm" id="CommentPublishForm" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="3" />
								
					<!-- CommentID -->
					<input type="hidden" name="commentOn" value="<?= $title ?>" />
					<?php $dataBean = new MDataBean("commentOn", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
					<br />
					
					<!-- DATE  -->
					<input type="hidden" name="begin" value="<?= date("d/m/Y") . " - " . date("H:i:s") ?>" />
					<?php $date = new MDataBean("begin", null, DATE); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($date)); ?>">
					
					<!-- TEXT -->
					<span>Add Comment :</span>
					<textarea name="data"></textarea>
					<?php $dataBean = new MDataBean("data", null, TEXT); ?>
					<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
					<br />
					
					<a href="#" data-role="button" onclick="document.CommentPublishForm.submit()" >Publicare</a>
				</form>
			</div>
		</div>
	<?php }
}
?>
