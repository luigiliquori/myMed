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
	function /*void*/ getArticle($application, $langue) {
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", "Lingua" . $langue);
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
						<span class="ui-li-aside ui-link" ><?= substr($controller->publisherID, 6) ?>
							<a href="mailto:<?= substr($controller->publisherID, 6) ?>" target="_blank"><?= substr($controller->publisherID, 6) ?></a>
						</span>
					</a>
				</li>
				<?php 
				$this->numberOfArticle++;
			}
		}
	}
	
	/**
	* Load all the article: French/Italiano
	*/
	private /*String*/ function getArticleList() { ?>
		<ul data-role="listview" data-filter="true" data-theme="c" data-divider-theme="b" >
			<li data-role="list-divider" data-theme='b'>Pending</li>
			<?php $this->getArticle( APPLICATION_NAME."_ADMIN", "italiano") ?>
			<?php $this->getArticle( APPLICATION_NAME."_ADMIN", "francese") ?>	
			<li data-role="list-divider" data-theme='b'>Validated</li>
			<?php $this->getArticle( APPLICATION_NAME, "italiano") ?>
			<?php $this->getArticle( APPLICATION_NAME, "francese") ?>
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
