<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/views/tabbar/MainView.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class BuildView3 extends MainView {
	
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
		parent::__construct("buildView3");
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
			<h1>Vue 3</h1>
			<a href="#Option" data-role="button" class="ui-btn-right" data-icon="gear" data-rel="dialog">Options</a>
		</div>
	<?php }
		
	protected /*void*/ function getMapFeature() { ?>
		<!-- MAP FEATURE -->
		<div data-role="collapsible" data-collapsed="false">
			<h3>Géolocalisation</h3>
			
			<label for="flip-a">Activer:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
			</select> 
			<br /><br />
			<label for="flip-a">Affichage de la carte:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getPublishFeature() { ?>
		<!-- PUBLISH FEATURE -->
		<div data-role="collapsible">
			<h3>Publication</h3>
			<label for="flip-a">Activer:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getSubscribeFeature() { ?>
		<!-- SUBSCRIBE FEATURE -->
		<div data-role="collapsible">
			<h3>Souscription</h3>
			<label for="flip-a">Activer:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getFindFeature() { ?>
		<!-- FIND FEATURE -->
		<div data-role="collapsible">
			<h3>Recherche</h3>
			<label for="flip-a">Activer:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getProfileFeature() { ?>
		<div data-role="collapsible" data-collapsed="true">
			<h3>Profile</h3>
			<label for="flip-a">Activer:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
			</select> 
		</div>
	<?php }
	
	protected /*void*/ function getSocialNetworkFeature() { ?>
		<!-- FRIENDS -->
		<div data-role="collapsible" data-collapsed="true">
		 	<h3>Reseaux Sociaux</h3>
		 	<label for="flip-a">Activer:</label>
			<select name="slider" id="flip-a" data-role="slider">
				<option value="off">Non</option>
				<option value="on">Oui</option>
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
				<a href="#buildView2" data-role="button" data-icon="arrow-l" data-transition="slide" data-direction="reverse">Vue Précédente</a>
				<a href="#buildView3" data-role="button" data-icon="arrow-r" data-iconpos="right">Vue Suivante</a>
			</div>
		</center>
		
		<?php parent::getContent(); ?>
	
		<center>
			<a href="#Validate" data-rel="dialog" data-role="button" data-theme="g" data-inline="true" data-icon="check" data-iconpos="top">Créer Application</a>
		</center>
	<?php }
	
}
?>