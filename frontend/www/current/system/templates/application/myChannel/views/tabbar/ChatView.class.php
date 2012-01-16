<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class ChatView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*Array*/ $quotes = array();
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Chat");
		
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "Category(fixed)");
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		// get results
		$i = 0;
		if($responseObject->status == 200) {
			foreach(json_decode($responseObject->data->results) as $result) {
					
				$request = new Request("FindRequestHandler", READ);
				$request->addArgument("application", APPLICATION_NAME);
				$request->addArgument("predicate", $result->predicate);
				$request->addArgument("user", $result->user);
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
					
				if($responseObject->status == 200) {
					foreach(json_decode($responseObject->data->details) as $details) {
						if($details->key == "Quote") {
							$this->quotes[$i] = $details->value;
						}
					}
				} else {
					echo '<script type="text/javascript">alert(\'Details fail: ' . $responsejSon . '\');</script>';
				}
				$i++;
			}
		} else {
			echo '<script type="text/javascript">alert(\'Normal Fail: ' . $responsejSon . '\');</script>';
		}
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" Style="text-align: left;">
		
			<!-- TEXT -->
			<h3>Test Channel</h3>
			<div id="chatTextArea" Style="position: relative; width: 100%; height: 200px; border: thin white solid; background-color: black;">
				<?php foreach ($this->quotes as $quote) { ?>
					<?= $quote ?><br />
				<?php } ?>
			</div>
		
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="2" />
				<br />
				
				<!-- KEYWORD -->
				<input type="text" name=Quote value="" />
				<?php $keyword = new MDataBean("Quote", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- CATEGORY (FIXED) -->
				<input type="hidden" name=Category value="fixed" />
				<?php $keyword2 = new MDataBean("Category", null, KEYWORD); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($keyword2)); ?>">
				
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" >Publish</a>
			</form>
		</div>
	<?php }
	
}
?>