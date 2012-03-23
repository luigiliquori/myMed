<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/views/home/MainView.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class BuildView1 extends MainView {
	
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
		parent::__construct("buildView1");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="a">
			<a href="#mainView" data-role="button" data-transition="slideup" data-icon="arrow-l">Retour</a>
			<h1>Vue 1</h1>
			<a href="#Option" data-role="button" class="ui-btn-right" data-icon="gear" data-rel="dialog">Options</a>
		</div>
	<?php }
		
	protected /*void*/ function getMapFeature() { ?>
		<!-- MAP FEATURE -->
		<div data-role="collapsible" data-collapsed="false">
			<h3>Géolocalisation</h3>
			
			<label>Activer:</label>
			<select name="slider" id="VIEW_1_GEOLOC" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select> 
			<br /><br />
			<label>Affichage de la carte:</label>
			<select name="slider" id="VIEW_1_MAP" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getPublishFeature() { ?>
		<!-- PUBLISH FEATURE -->
		<div data-role="collapsible">
			<h3>Publication</h3>
			<label>Activer:</label>
			<select name="slider" id="VIEW_1_PUBLISH" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select>
			<br /><br />
			<div data-role="collapsible" data-theme="c" data-content-theme="c" data-inline="true">
			   <h3>Options Avancées</h3>
			   <h4>Nombre d'ontologies</h4>
			   <label for="VIEW_1_PUBLISH_keyword">Mot Cléf</label>
			   <input type="range" name="VIEW_1_PUBLISH_keyword" id="VIEW_1_PUBLISH_keyword" value="1" min="0" max="3" />
			   <label for="VIEW_1_PUBLISH_address">Adresse (position GPS) :</label>
			   <input type="range" name="VIEW_1_PUBLISH_address" id="VIEW_1_PUBLISH_address" value="1" min="0" max="3" />
			   <label for="VIEW_1_PUBLISH_date">Date :</label>
			   <input type="range" name="VIEW_1_PUBLISH_date" id="VIEW_1_PUBLISH_date" value="1" min="0" max="3" />
			</div>
		</div>
	<?php }
	
	protected /*void*/ function getSubscribeFeature() { ?>
		<!-- SUBSCRIBE FEATURE -->
		<div data-role="collapsible">
			<h3>Souscription</h3>
			<label>Activer:</label>
			<select name="slider" id="VIEW_1_SUBSCRIBE" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select>
			<br /><br />
			<div data-role="collapsible" data-theme="c" data-content-theme="c" data-inline="true">
			   <h3>Options Avancées</h3>
			   <h4>Nombre d'ontologies</h4>
			   <label for="VIEW_1_SUBSCRIBE_keyword">Mot Cléf</label>
			   <input type="range" name="VIEW_1_SUBSCRIBE_keyword" id="VIEW_1_SUBSCRIBE_keyword" value="1" min="0" max="3" />
			   <label for="VIEW_1_SUBSCRIBE_address">Adresse (position GPS) :</label>
			   <input type="range" name="VIEW_1_SUBSCRIBE_address" id="VIEW_1_SUBSCRIBE_address" value="1" min="0" max="3" />
			   <label for="VIEW_1_SUBSCRIBE_date">Date :</label>
			   <input type="range" name="VIEW_1_SUBSCRIBE_date" id="VIEW_1_SUBSCRIBE_date" value="1" min="0" max="3" />
			</div>
		</div>
	<?php }
	
	protected /*void*/ function getFindFeature() { ?>
		<!-- FIND FEATURE -->
		<div data-role="collapsible">
			<h3>Recherche</h3>
			<label>Activer:</label>
			<select name="slider" id="VIEW_1_FIND" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select>
			<br /><br />
			<div data-role="collapsible" data-theme="c" data-content-theme="c" data-inline="true">
			   <h3>Options Avancées</h3>
			   <h4>Nombre d'ontologies</h4>
			   <label for="VIEW_1_FIND_keyword">Mot Cléf</label>
			   <input type="range" name="VIEW_1_FIND_keyword" id="VIEW_1_FIND_keyword" value="1" min="0" max="3" />
			   <label for="VIEW_1_FIND_address">Adresse (position GPS) :</label>
			   <input type="range" name="VIEW_1_FIND_address" id="VIEW_1_FIND_address" value="1" min="0" max="3" />
			   <label for="VIEW_1_FIND_date">Date :</label>
			   <input type="range" name="VIEW_1_FIND_date" id="VIEW_1_FIND_date" value="1" min="0" max="3" />
			</div>
		</div>
	<?php }
	
	protected /*void*/ function getProfileFeature() { ?>
		<div data-role="collapsible" data-collapsed="true">
			<h3>Profile</h3>
			<label>Activer:</label>
			<select name="slider" id="VIEW_1_PROFILE" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getSocialNetworkFeature() { ?>
		<!-- FRIENDS -->
		<div data-role="collapsible" data-collapsed="true">
		 	<h3>Reseaux Sociaux</h3>
		 	<label>Activer:</label>
			<select name="slider" id="VIEW_1_SOCIAL_NETWORK" data-role="slider">
				<option value="false">Non</option>
				<option value="true">Oui</option>
			</select> 
		 </div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*void*/ function getContent() { ?>
		<center>
			<div data-role="controlgroup" data-type="horizontal">
				<br>
				<a href="#buildView1" data-role="button" data-icon="arrow-l" data-transition="slide" data-direction="reverse">Vue Précédente</a>
				<a href="#buildView2" data-role="button" data-icon="arrow-r" data-iconpos="right" data-transition="slide">Vue Suivante</a>
			</div>
		</center>
		
		<?php parent::getContent(); ?>
	
		<center>
			<a href="#Validate" data-rel="dialog" data-role="button" data-theme="g" data-inline="true" data-icon="check" data-iconpos="top">Créer Application</a>
		</center>
	<?php }
	
}
?>