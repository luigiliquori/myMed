<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';
require_once 'lib/dasp/request/Request.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class PublishView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $myRivieraPOIs_myMed;
	private /*String*/ $myRivieraPOIs_carf;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("myRivieraAdmin");
		// USER POINT
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "keyword(myRivieraPOIs_myMed)");
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$this->myRivieraPOIs_myMed = "";
		if($responseObject->status == 200) {
			$resArray = json_decode($responseObject->data->results);
			$this->myRivieraPOIs_myMed = $resArray[0]->data;
		}
		
		// CARF POINT
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "keyword(myRivieraPOIs_carf)");
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$this->myRivieraPOIs_carf = "";
		if($responseObject->status == 200) {
			$resArray = json_decode($responseObject->data->results);
			$this->myRivieraPOIs_carf = $resArray[0]->data;
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
		<div class="content">
			
			<!-- USER POINT -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="false">
			   <h3>USER POINT</h3>
			   <form action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm1" id="<?= APPLICATION_NAME ?>PublishForm1" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="2" />
					
					<!-- KEYWORD -->
					<input type="hidden" name="keyword" value="myRivieraPOIs_myMed"/>
					<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
					
					<textarea name="data" rows="" cols=""><?= $this->myRivieraPOIs_myMed ?></textarea>
					<?php $text = new MDataBean("data", null, TEXT); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($text)); ?>">
					<br />
					
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm1.submit()">Publish</a>
				</form>
			</div>
			
		
			<!-- CARF POINT -->
			<div data-role="collapsible" data-theme="a" data-content-theme="c" data-collapsed="false">
			   <h3>CARF POINT</h3>
				<form action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm2" id="<?= APPLICATION_NAME ?>PublishForm2" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="2" />
					
					<!-- KEYWORD -->
					<input type="hidden" name="keyword" value="myRivieraPOIs_carf"/>
					<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
					
					<!-- TEXT -->
					<textarea name="data" rows="" cols=""><?= $this->myRivieraPOIs_carf ?></textarea>
					<?php $text = new MDataBean("data", null, TEXT); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($text)); ?>">
					<br />
					
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm2.submit()">Publish</a>
				</form>
			</div>
		</div>
	<?php }
	
}
?>
