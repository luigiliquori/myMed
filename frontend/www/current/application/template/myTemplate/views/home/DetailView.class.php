<?php

require_once '../../../lib/dasp/beans/MDataBean.class.php';
require_once '../../../lib/dasp/request/Request.class.php';
require_once '../../../lib/dasp/request/Reputation.class.php';

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
		
			<a href="#ResultView" data-role="button" data-direction="reverse" data-inline="true"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br />
 			<?php 
			if(isset($_POST['method']) && $_POST['method'] == "getDetail" && $this->handler->getSuccess()) {
				$details = json_decode($this->handler->getSuccess());
				$_SESSION[APPLICATION_NAME]['details'] = $details;
				$user = $_POST['user'];
				$predicate = $_POST['predicate'];
				$_SESSION[APPLICATION_NAME]['details']['predicate'] = $predicate;
				$_SESSION[APPLICATION_NAME]['details']['user'] = $user;
			} else {
				$details = $_SESSION[APPLICATION_NAME]['details'];
				$user = $_SESSION[APPLICATION_NAME]['details']['user'];
				$predicate = $_SESSION[APPLICATION_NAME]['details']['predicate'];
			}
			?>
			<!-- TEXT -->
			<?php $values = array(); ?>
			<?php foreach($details as $detail) {
				if(isset($detail->key) && isset($detail->value)) {
					$values[$detail->key] = urldecode($detail->value);
				}
			} ?>
			<h1><?= $values['data'] ?></h1>
			<div id="article"><?= $values['text'] ?></div>
			<br />
			
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
		    <div style="position: relative; top: 3px; left:35px; height: 30px;">
		    <?php 
		    	echo $profile->firstName; 
		    	echo $profile->lastName . "-"; 
		    	echo $values['begin'];
		    ?> 
		    </div>
		    
		    <!-- Reputation -->
		    <div>
		    	<?php 
		    	$request = new Request("ReputationRequestHandler", READ);
		    	$request->addArgument("application",  APPLICATION_NAME);
		    	$request->addArgument("producer",  $profile->id);
		    	$request->addArgument("consumer",  $_SESSION['user']->id);
		    	$responsejSon = $request->send();
		    	$responseObject = json_decode($responsejSon);
		    	if(isset($responseObject->dataObject->reputation)){
			    	$i=0;
			    	$reputation = $responseObject->dataObject->reputation * 100;
			    	echo $reputation . "%";
// 		    		while($i<$reputation){
// 				    	echo "+";
// 				    	$i+=20;
// 			    	}
// 		    		while($i<100){
// 				    	echo "-";
// 				    	$i+=20;
// 			    	}
		    	}
		    	
		    	?>
		    	<?php $date = date("d/m/Y") ?>
		    	<form id="StartInteractionForm" action="#DetailView" method="post" name="StartInteractionForm" id="StartInteractionForm" enctype="multipart/form-data">
		    		<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="startInteraction" />
					<input type="hidden" name="producer" value="<?= $user ?>" />
					<input type="hidden" name="consumer" value="<?= $_SESSION['user']->id ?>" />
					<input type="hidden" name="start" value="<?= time() ?>" />
					<input type="hidden" name="end" value="<?= time() ?>" />
					<input type="hidden" name="predicate" value="<?= $predicate ?>" />
					<input type="hidden" name="feedback" value="" id="feedback"/>
		    	
		    		<a data-role="button" data-icon="plus" data-iconpos="notext" data-inline="true" onclick="$('#feedback').val('1'); document.StartInteractionForm.submit();"></a>
		    		<a data-role="button" data-icon="minus" data-iconpos="notext" data-inline="true" onclick="$('#feedback').val('0'); document.StartInteractionForm.submit();"></a>
		    	</form>
		    </div>
		    
	    	
	    	<!-- COMMENT -->
	    	<div data-role="collapsible"  data-content-theme="d">
	    		<h3>Comment</h3>
	    		<?php if(!USER_CONNECTED) {?>
	    					<span>Please login before using this feature...</span>
	    		<?php } else {
			    	$request = new Request("FindRequestHandler", READ);
			    	$request->addArgument("application", APPLICATION_NAME);
			    	$request->addArgument("predicate", "commentOn" . $values['data']);
			    	$responsejSon = $request->send();
			    	$responseObject = json_decode($responsejSon);
			    	if($responseObject->status == 200) {
				    	foreach(json_decode($responseObject->data->results) as $comment) { ?>
				    		<p><?= $comment->data ?></p>
				    		<p><?= $comment->publisherName ?></p>
				    		<p><?= $comment->begin ?></p>
				    		<br /><br />
				    	<?php } ?>
					<?php } else { ?>
						<p>0 comments</p>
					<?php } ?>
					<form  action="#DetailView" method="post" name="CommentPublishForm" id="CommentPublishForm" enctype="multipart/form-data">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="publish" />
						<input type="hidden" name="numberOfOntology" value="4" />
						
						<!-- CommentGroup -->
						<input type="hidden" name="commentGroup" value="<?= APPLICATION_NAME ?>" />
						<?php $dataBean = new MDataBean("commentGroup", null, KEYWORD); ?>
						<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
						<br />
									
						<!-- CommentID -->
						<input type="hidden" name="commentOn" value="<?= $values['data'] ?>" />
						<?php $dataBean = new MDataBean("commentOn", null, KEYWORD); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
						<br />
						
						<!-- DATE  -->
						<input type="hidden" name="begin" value="<?= date("d/m/Y") . " - " . date("H:i:s") ?>" />
						<?php $date = new MDataBean("begin", null, DATE); ?>
						<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
						
						<!-- TEXT -->
						<span>Add Comment :</span>
						<textarea name="data"></textarea>
						<?php $dataBean = new MDataBean("data", null, TEXT); ?>
						<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
						<br />
						
						<a href="#" data-role="button" onclick="document.CommentPublishForm.submit()" >Publicare</a>
					</form>
				<?php } ?>
			</div>
		</div>
	<?php }
}
?>