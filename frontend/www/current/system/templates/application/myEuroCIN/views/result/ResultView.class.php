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
class ResultView extends MyApplication {
	
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
			<?php if(isset($_GET['publish'])) { ?>
				<h2>info</h2>
			<?php } else { ?>
				<h2>Résultats</h2>
			<?php } ?>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<?php if($this->handler->getSuccess() && $_POST['method'] == "find") { ?>
				<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a" >
				<?php $i=0 ?>
				<?php foreach(json_decode($this->handler->getSuccess()) as $controller) { ?>
					<li>
						
						<!-- RESULT DETAILS -->
						<form action="#" method="post" name="getDetailForm<?= $i ?>">
							<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
							<input type="hidden" name="method" value="getDetail" />
							<input type="hidden" name="user" value="<?= $controller->publisherID ?>" />
							<input type="hidden" name="predicate" value="<?= $controller->predicate ?>" />
						</form>
						
						<a href="#" onclick="document.getDetailForm<?= $i ?>.submit()"><?= $controller->data ?></a>
					</li>
					<?php $i++ ?>
				<?php } ?>
				</ul>
			<?php } else if($this->handler->getSuccess() && ($_POST['method'] == "publish" || $_POST['method'] == "subscribe")) { ?>
				<h2 style="color:green;">Message envoyé avec succès</h2>
				<a href="?application=<?= APPLICATION_NAME ?>" data-role="button" rel="external">Retour</a>
			<?php } else { ?>
				<h2 style="color:red;"><?= $this->handler->getError() ?></h2>
			<?php } ?>
		</div>
	<?php }
}
?>
