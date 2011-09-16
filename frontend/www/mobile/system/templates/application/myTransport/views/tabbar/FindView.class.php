<?php

require_once 'system/templates/application/myTransport/MyTransport.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView extends MyTransport {
	
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
		parent::__construct("Rechercher");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" data-role="content">
			<form  action="#" method="post" name="myTransportFindForm" id="myTransportFindForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="myTransport" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="3" />
				
				<!-- DATE -->
				Date :<br />
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/>
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($date)); ?>">
				<br />
				
				<!-- GPS -->
				Ville de départ :<br />
				<input type="text" name="Depart" value=""  data-inline="true"/>
				<?php $gps = new MDataBean("Depart", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- GPS -->
				Ville d'arrivée :<br />
				<input type="text" name="Arrivée" value=""  data-inline="true"/>
				<?php $gps = new MDataBean("Arrivée", null, GPS); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($gps)); ?>">

				<br /><br />
				<a href="#" data-role="button"onclick="document.myTransportFindForm.submit()">Find</a>	
			</form>
		</div>
	<?php }
	
}
?>