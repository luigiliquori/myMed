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
class CommentView extends MainView {
	
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
		parent::__construct("CommentView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Load all the article: French/Italiano
	*/
	private /*String*/ function getArticle() { ?>
		<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a" >
				<?php $i=0 ?>
				<?php 
				$request = new Request("FindRequestHandler", READ);
		    	$request->addArgument("application", APPLICATION_NAME . "_ADMIN");
		    	$request->addArgument("predicate", "commentGroup" .  APPLICATION_NAME);
		    	$responsejSon = $request->send();
		    	$responseObject = json_decode($responsejSon);
		    	if($responseObject->status == 200) {
			    	foreach(json_decode($responseObject->data->results) as $controller) { ?>
						<li>
							<!-- RESULT DETAILS -->
							<form action="#EditCommentView" method="post" name="getCommentDetailForm<?= $i ?>">
								<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>_ADMIN" />
								<input type="hidden" name="method" value="getDetail" />
								<input type="hidden" name="user" value="<?= $controller->publisherID ?>" />
								<input type="hidden" name="predicate" value="<?= $controller->predicate ?>" />
							</form>
							<a href="#" onclick="document.getCommentDetailForm<?= $i ?>.submit()">
								<?= $controller->data ?>
								<span style="margin-right:10px;" class="ui-li-aside ui-link" ><?= $controller->publisherName ?></span>
							</a>
						</li>
						<?php $i++ ?>
					<?php } ?>
				<?php } ?>
			</ul>
			<br/><br/>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
			$this->getArticle();
		echo '</div>';
	}
}
?>
