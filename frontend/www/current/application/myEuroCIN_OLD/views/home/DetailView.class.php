<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
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
		
			<a href="#ResultView" data-role="button" data-direction="reverse" data-inline="true"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br />
 			<?php 
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
		    	<?= $profile->firstName ?> 
		    	<?= $profile->lastName ?> - 
		    	<?= $values['begin'] ?>
		    </div>
	    	
	    	<!-- COMMENT -->
	    	<div data-role="collapsible"  data-content-theme="d">
	    		<h3>Comment</h3>
	    		<?php if($_SESSION['user']->id == VISITOR_ID) {?>
	    					<span>Please login before using this feature...</span>
	    		<?php } else {
			    	$request = new Request("FindRequestHandler", READ);
			    	$request->addArgument("application", APPLICATION_NAME);
			    	$request->addArgument("predicate", "commentOn" . $values['data']);
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
						<p>0 comments</p>
					<?php } ?>
					<form  action="#DetailView" method="post" name="CommentPublishForm" id="CommentPublishForm" enctype="multipart/form-data">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>_ADMIN" />
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
						
						<a href="#" data-role="button" onclick="document.CommentPublishForm.submit()" >Postare</a>
					</form>
				<?php } ?>
			</div>
		</div>
	<?php }
}
?>
