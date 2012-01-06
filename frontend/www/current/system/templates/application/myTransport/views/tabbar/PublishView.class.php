<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

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
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="5" />
				
				<!-- DATE -->
				Date :<br />
				<input type="date" name="Date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="c"/>
				<?php $date = new MDataBean("Date", null, DATE); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($date)); ?>">
				<br />
				
				<!-- GPS -->
				Ville de départ :<br />
				<input type="text" name="Depart" value=""  data-inline="true" data-theme="c"/>
				<?php $gps = new MDataBean("Depart", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- GPS -->
				Ville d'arrivée :<br />
				<input type="text" name="Arrivée" value=""  data-inline="true" data-theme="c"/>
				<?php $gps = new MDataBean("Arrivée", null, GPS); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- ENUM -->
				Type de vehicule :<br />
				<select name="Type" data-theme="b">
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
				Commentaires :<br />
				<textarea name="Commentaires" rows="" cols="" data-theme="c"></textarea>
				<?php $text = new MDataBean("Commentaires", null, TEXT); ?>
				<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($text)); ?>">
				<br />
				
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()">Publish</a>
			</form>
		</div>
	<?php }
	
}
?>