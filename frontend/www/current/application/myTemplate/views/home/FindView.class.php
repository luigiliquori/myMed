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
class FindView extends MainView {
	
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
		parent::__construct("FindView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
		
			<form action="#ResultView" method="post" name="FindForm" id="FindForm" enctype="multipart/form-data">
				<!-- Define the method to call -->
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="numberOfOntology" value="3" />
				<br />
				
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
				
				<center><a href="#" data-role="button" onclick="document.FindForm.submit()" data-inline="true" data-icon="search"><?= $_SESSION['dictionary'][LG]["view2"] ?></a></center>
			</form>
		</div>
	<?php }
}
?>
