<?php

require_once 'system/templates/application/myTransport/MyTransport.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class PublishView extends MyTransport {
	
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
		parent::__construct("Publier");
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
			<form  action="#" method="post" name="myTransportPublishForm" id="myTransportPublishForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="myTransport" />
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="5" />
				
				<!-- DATE -->
				Date :<br />
				<input type="date" name="Date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/>
				<?php $date = new MDataBean("Date", null, DATE); ?>
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
				<br />
				
				<!-- ENUM -->
				Type de vehicule:  :<br />
				<select name="Type" data-theme="a">
					<option value="Compacte"> Compacte - Berline - Coupé</option>
					<option value="Cabriolet">Cabriolet</option>
					<option value="Break">Break - Monospace</option>
					<option value="4x4">4x4</option>
					<option value="Sportive">Sportive</option>
					<option value="Collection">De collection</option>
					<option value="Utilitaires">Utilitaires - véhicules de société</option>
					<option value="Sans">Véhicules sans permis</option>
				</select>
				<?php $enum = new MDataBean("Type", null, TEXT); // TEXT Because we don't want to use this field as a predicate ?>
				<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($enum)); ?>">
				<br />
				
				<!-- TEXT -->
				Commentaires : :<br />
				<textarea name="Commentaires" rows="" cols=""></textarea>
				<?php $text = new MDataBean("Commentaires", null, TEXT); ?>
				<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($text)); ?>">
				<br />
				
				<a href="#" data-role="button" onclick="document.myTransportPublishForm.submit()">Publish</a>
			</form>
		</div>
	<?php }
	
}
?>