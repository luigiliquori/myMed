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
class EditCommentView extends MainView {
	
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
		parent::__construct("EditCommentView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Edit the article
	*/
	private /*String*/ function getContentToValidate() { ?>
	
		<!-- getValue -->
		<?php $values = array(); ?>
		<?php foreach(json_decode($this->handler->getSuccess()) as $details) {
			$values[$details->key] = urldecode($details->value);
		} ?>
<<<<<<< HEAD
		
		<!-- AUTHOR -->
		
    	<?php
    	if(isset($_POST['user'])) {
    		$request = new Request("ProfileRequestHandler", READ);
    		$request->addArgument("id",  $_POST['user']);
    		$responsejSon = $request->send();
    		$responseObject = json_decode($responsejSon);
    		$profile = json_decode($responseObject->data->user);
    		if($profile->profilePicture != "") { ?>
    			<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="30" height="30" style="position: absolute;">
    		<?php } else { ?>
    			<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="30" height="30" style="position: absolute;">
    		<?php }	?>
    		<div style="position: relative; top: 3px; left:35px; height: 30px;">
		    	<a href="mailto:<?= $profile->email ?>" target="_blank">
		    		<?= $profile->firstName ?> &nbsp;
		    		<?= $profile->lastName ?> 
		    	</a> &nbsp;
		    	<?= $values['begin'] ?>
		    </div>
		    <br />
	
    	<?php }?>
=======
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
		<form action="#CommentView" method="post" name="EditCommentForm" id="EditCommentForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" id="EditCommentApplication" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" id="EditCommentMethod" name="method" value="publish" />
<<<<<<< HEAD
			<input type="hidden" name="numberOfOntology" value="5" />
=======
			<input type="hidden" name="numberOfOntology" value="4" />
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
			
			<!-- CommentGroup -->
			<input type="hidden" name="commentGroup" value="<?= $values["commentGroup"] ?>" />
			<?php $dataBean = new MDataBean("commentGroup", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
						
			<!-- CommentID -->
			<span>Testo :</span>
			<input type="text" name="commentOn" value="<?= $values["commentOn"] ?>" readonly="readonly"/>
			<?php $dataBean = new MDataBean("commentOn", null, KEYWORD); ?>
			<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- DATE  -->
			<span>Date :</span>
			<input type="text" name="begin" value="<?= $values["begin"] ?>"  readonly="readonly"/>
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
			<br />
			
			<!-- TEXT -->
			<span>Comment :</span>
			<textarea name="data"><?= $values["data"] ?></textarea>
			<?php $dataBean = new MDataBean("data", null, TEXT); ?>
			<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
<<<<<<< HEAD
			<!-- PUBLISHER -->
			<input type="hidden" name="publisher" value="<?= $_POST['user'] ?>" />
			
			<center>
			<a href="#" data-role="button" onclick="document.EditCommentForm.submit()" data-theme="g" data-inline="true">Validate</a>
			<a href="#" data-role="button" onclick="$('#EditCommentApplication').val('<?= APPLICATION_NAME ?>'); $('#EditCommentMethod').val('delete'); document.EditCommentForm.submit()" data-theme="r" data-inline="true">Reject</a>
=======
			<center>
			<a href="#" data-role="button" onclick="document.EditCommentForm.submit()" data-theme="g" data-inline="true">Validate</a>
			<a href="#" data-role="button" onclick="$('#EditCommentApplication').val('<?= APPLICATION_NAME ?>_ADMIN'); $('#EditCommentMethod').val('delete'); document.EditCommentForm.submit()" data-theme="r" data-inline="true">Reject</a>
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
			</center>
		</form>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
			$this->getContentToValidate();
		echo '</div>';
	}
}
?>
