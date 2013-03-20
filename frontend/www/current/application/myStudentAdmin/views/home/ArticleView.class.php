<!--
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
 -->
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
class ArticleView extends MainView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	private /*int*/ $numberOfArticle;	
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($handler) {
		$this->handler = $handler;
		$this->numberOfArticle = 0;
		parent::__construct("ArticleView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	function /*void*/ getArticle($application, $Categoria) {
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", "Categoria" . $Categoria);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			foreach(json_decode($responseObject->data->results) as $controller) { ?>
				<li>
					<!-- RESULT DETAILS -->
					<form action="#EditArticleView" method="post" name="getDetailForm<?= $this->numberOfArticle ?>">
						<input type="hidden" name="application" value="<?= $application ?>" />
						<input type="hidden" name="method" value="getDetail" />
						<input type="hidden" name="user" value="<?= $controller->publisherID ?>" />
						<input type="hidden" name="predicate" value="<?= $controller->predicate ?>" />
					</form>
					<a href="#" onclick="document.getDetailForm<?= $this->numberOfArticle ?>.submit()">
						<?= $controller->data ?>
					</a>
				</li>
				<?php 
				$this->numberOfArticle++;
			}	
		}else{
		}
	}
	
		
	
	/**
	* Load all the article: French/Italiano
	*/
	private /*String*/ function getArticleList() { ?>
		<ul data-role="listview" data-filter="true" data-theme="c" data-divider-theme="b" >
			<li data-role="list-divider" data-theme='b'>Pending</li>
			<?php $this->getArticle( APPLICATION_NAME."_ADMIN", "Job") ?>
			<?php $this->getArticle( APPLICATION_NAME."_ADMIN", "Stage") ?>	
			<?php $this->getArticle( APPLICATION_NAME."_ADMIN", "Tesi") ?>
			<?php $this->getArticle( APPLICATION_NAME."_ADMIN", "Appunti") ?>
			<li data-role="list-divider" data-theme='b'>Validated</li>
			<?php $this->getArticle( APPLICATION_NAME, "Job") ?>
			<?php $this->getArticle( APPLICATION_NAME, "Stage") ?>	
			<?php $this->getArticle( APPLICATION_NAME, "Tesi") ?>
			<?php $this->getArticle( APPLICATION_NAME, "Appunti") ?>
		</ul>
		<br/><br/>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
			$this->getArticleList();
		echo '</div>';
	}
}
?>
