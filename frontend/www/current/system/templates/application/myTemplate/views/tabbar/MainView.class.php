<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class MainView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("mainView");
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
		
				
			<div data-role="collapsible-set">
			
				<!-- MAP FEATURE -->
				<div data-role="collapsible" data-collapsed="false">
					<h3>Map Feature</h3>
					<div id="<?= APPLICATION_NAME ?>Map" style="position: relative; width: 100%; height: 200px;"></div>
					<h4>FOCUS ON :</h4>
					<input id="formatedAddress0" type="text" value="" /><br />
					<a href="#" data-role="button" onclick="refreshMap($('#formatedAddress0').val());" data-theme="d">Refresh</a>
				</div>
				
				<!-- PUBLISH FEATURE -->
				<div data-role="collapsible">
					<h3>Publish Feature</h3>
					<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="publish" />
						<input type="hidden" name="numberOfOntology" value="4" />
						
						<!-- KEYWORD -->
						<h4>KEYWORD :</h4>
						<input type="text" name="keyword" value=""  data-inline="true"/><br />
						<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
						<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
						
						<!-- GPS -->
						<h4>GPS POSITION :</h4>
						<input id="formatedAddress1" type="text" name="gps" value=""  data-inline="true"/>
						<?php $gps = new MDataBean("gps", null, GPS); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
						<br />
						
						<!-- DATE -->
						<h4>DATE :</h4>
						<input type="date" name="date" data-theme="a" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
						<?php $date = new MDataBean("date", null, DATE); ?>
						<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
						<br /><br />
						
						<!-- TEXT -->
						<h4>TEXT :</h4>
						<textarea name="text" rows="" cols=""></textarea>
						<?php $text = new MDataBean("text", null, TEXT); ?>
						<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($text)); ?>">
						<br />
						
						<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" data-theme="d">Publish</a>
					</form>
				</div>
				
				<!-- SUBSCRIBE FEATURE -->
				<div data-role="collapsible">
					<h3>Subscribe Feature</h3>
					<form  action="#" method="post" name="<?= APPLICATION_NAME ?>SubscribeForm" id="<?= APPLICATION_NAME ?>SubscribeForm">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="subscribe" />
						<input type="hidden" name="numberOfOntology" value="3" />
						
						<!-- KEYWORD -->
						<h4>KEYWORD :</h4>
						<input type="text" name="keyword" value=""  data-inline="true"/><br />
						<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
						<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
						
						<!-- GPS -->
						<h4>GPS POSITION :</h4>
						<input id="formatedAddress2" type="text" name="gps" value=""  data-inline="true"/>
						<?php $gps = new MDataBean("gps", null, GPS); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
						<br />
						<!-- DATE -->
						<h4>DATE :</h4>
						<input type="date" name="date" data-theme="a" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
						<?php $date = new MDataBean("date", null, DATE); ?>
						<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
		
						<br /><br />
						<a href="#" data-role="button"onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()" data-theme="d">Subscribe</a>	
					</form>
				</div>
				
				<!-- FIND FEATURE -->
				<div data-role="collapsible">
				<h3>Find Feature</h3>
					<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="find" />
						<input type="hidden" name="numberOfOntology" value="3" />
						
						<!-- KEYWORD -->
						<h4>KEYWORD :</h4>
						<input type="text" name="keyword" value=""  data-inline="true"/><br />
						<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
						<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
						
						<!-- GPS -->
						<h4>GPS POSITION :</h4>
						<input id="formatedAddress3" type="text" name="gps" value=""  data-inline="true"/>
						<?php $gps = new MDataBean("gps", null, GPS); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
						<br />
						
						<!-- DATE -->
						<h4>DATE :</h4>
						<input type="date" name="date" data-theme="a" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
						<?php $date = new MDataBean("date", null, DATE); ?>
						<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
		
						<br /><br />
						<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()" data-theme="d">Find</a>	
					</form>
				</div>
				
			</div>
			
		</div>
	<?php }
	
}
?>