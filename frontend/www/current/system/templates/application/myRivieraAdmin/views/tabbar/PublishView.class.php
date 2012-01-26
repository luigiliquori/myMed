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
	private /*String*/ $kml;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("myRivieraAdmin");
		
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "keyword(myRivieraAdmin)");
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$this->kml = "";
		if($responseObject->status == 200) {
			$resArray = json_decode($responseObject->data->results);
			$this->kml = $resArray[0]->data;
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
			<form  action="#" method="post"  enctype="multipart/form-data">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="2" />
				
				<!-- KEYWORD -->
				<input type="hidden" name="keyword" value="myRivieraAdmin"/>
				<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- TEXT -->
				POI - KML :<br />
				<textarea name="data" rows="" cols=""><?= $this->kml ?></textarea>
				<?php $text = new MDataBean("data", null, TEXT); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($text)); ?>">
				<br />
				
				<input type="submit" value="Publish" onclick="alert('request sent!')"/>
			</form>
		</div>
	<?php }
	
}
?>
