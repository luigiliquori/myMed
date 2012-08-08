<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class PublishView extends MainView {
	
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
		parent::__construct("PublishView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getPublishContent() { ?>
		<form  action="#PublishView" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="5" />
			
			<!-- Key1 -->
			<span><?= $_SESSION['dictionary'][LG]["ontology0"] ?> :</span>
			<input type="text" name="data" value="" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Key2 -->
			<span><?= $_SESSION['dictionary'][LG]["ontology1"] ?> :</span>
			<input type="text" name="key2" value="" />
			<?php $dataBean = new MDataBean("key2", null, KEYWORD); ?>
			<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Key3 -->
			<span><?= $_SESSION['dictionary'][LG]["ontology2"] ?> :</span>
			<input type="text" name="key3" value="" />
			<?php $dataBean = new MDataBean("key3", null, KEYWORD); ?>
			<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- TEXT -->
			<span><?= $_SESSION['dictionary'][LG]["ontology3"] ?> :</span>
			<textarea id="CLEeditor" name="text"></textarea>
			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- DATE  -->
			<input type="hidden" name="begin" value="<?= date("d/m/Y") . " - " . date("H:i:s") ?>" />
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($date)); ?>">
			
			<center>
			<a href="#" data-role="button" data-inline="true" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" ><?= $_SESSION['dictionary'][LG]["view3"] ?></a>
			</center>
		</form>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getResultContent() {
		if($succes = $this->handler->getSuccess()) { 
			echo $_SESSION['dictionary'][LG]["requestSent"];
		} else {
			echo $this->handler->getError();
		}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
		if(isset($_POST['method']) && $_POST['method'] == "publish" && ($this->handler->getError() || $this->handler->getSuccess())) {
			$this->getResultContent();
		} else {
			$this->getPublishContent();
		}
		echo '</div>';
	}
	
}
?>
