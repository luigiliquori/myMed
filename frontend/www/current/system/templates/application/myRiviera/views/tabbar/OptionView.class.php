<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 *
 * Represent the template
 * @author lvanni
 *
 */
class OptionView extends MyApplication {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	private /*String[]*/ $filterList;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct("Option");
		$this->handler = $handler;
		$this->filterList = array("mymed", "carf");
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */

	public /*String*/ function getHeader() { ?>
	<div data-role="header" data-theme="b">
		<h1>Options</h1>
		<a href="#Find" data-role="button" class="ui-btn-left"
			data-icon="arrow-l">Retour</a>
	</div>
	
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
	<!-- CONTENT -->
	<div data-role="content" Style="font-size: 10pt;">
		<!-- UPDATE POIs -->
		<div data-role="collapsible-set">
			<!-- POIs - Filter -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c">
				<h3>Points d'interêts</h3>
				<fieldset id="<?= APPLICATION_NAME ?>Filter" data-role="controlgroup" data-type="horizontal">	
					<?php foreach ($this->filterList as $filter) { ?>
					<input type="checkbox" name="<?= $filter ?>" id="<?= $filter ?>"
						class="custom" checked="checked" /> <label for="<?= $filter ?>"><?= $filter ?>
					</label><?php } ?>
				</fieldset>
			</div>
			
			<!-- Persistence -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c">
				<h3>Persistence des points d'intérêts</h3>
				<fieldset id="flip-persistence" data-role="controlgroup" data-type="horizontal" style="width:200px;">	
					<select name="flip-per" id="flip-per" data-role="slider" onchange="if(!$(this).val()) {clearMarkers();}">
						<option value=''>non</option>
						<option value='1'>oui</option>
					</select>
				</fieldset>
			</div>
	
			<!-- POIs - radius -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c" style="text-align: left;">
				<h3>Rayon de recherche</h3>
				<input type="range" name="slider-radius" id="slider-radius" value="<?= TARGET == "mobile" ? "2" : "5" ?>00" min="100"
					max="1000" data-theme="b" /> <span style="display: inline;">mètres</span>
			</div>
	
			<!-- Search Option -->
			<div id="cityway-search" data-role="collapsible" data-collapsed="true"
				data-theme="b" data-content-theme="c">
				<h3>Type de Trajet Cityway</h3>
				<fieldset data-role="controlgroup" data-type="horizontal" >
					<input type="radio" name="radio-choice" id="radio-choice1" value="fastest" checked="checked" />
					<label for="radio-choice1">le	+ rapide</label>
					<input type="radio" name="radio-choice" id="radio-choice2" value="lessChanges" />
					<label for="radio-choice2">le - de chgt</label>
				</fieldset>
				<fieldset data-role="controlgroup" data-type="horizontal">
					<input type="checkbox" name="checkbox" id="checkbox0"	checked="checked" /><label for="checkbox0">Bus</label>
					<input type="checkbox" name="checkbox" id="checkbox2" checked="checked" /><label for="checkbox2">Car</label>
					<input type="checkbox" name="checkbox" id="checkbox3" checked="checked" /><label for="checkbox3">Train</label>
					<input type="checkbox" name="checkbox" id="checkbox4"	checked="checked" /><label for="checkbox4">Tram</label>
					<input type="checkbox" name="checkbox" id="checkbox5" checked="checked" /><label for="checkbox5">Ter</label>
					<input type="checkbox" name="checkbox" id="checkbox17" checked="checked" /><label for="checkbox17">Nav_élec</label>
					<input type="checkbox" name="checkbox" id="checkbox19" checked="checked" /><label	for="checkbox19">Tgv</label>
				</fieldset>
			</div>
	
			<!-- Profile -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c">
				<h3>Profile</h3>
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
				<?php } ?>
					<br />
					Prenom: <?= $_SESSION['user']->firstName ?><br />
					Nom: <?= $_SESSION['user']->lastName ?><br />
					Date de naissance: <?= $_SESSION['user']->birthday ?><br />
					eMail: <?= $_SESSION['user']->email ?><br />
					<div data-role="controlgroup" data-type="horizontal">
						<a href="#inscription" data-role="button" data-rel="dialog" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a>
					<?php if(TARGET == "desktop") { ?>
						<a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a>
					<?php } else { ?>
						<a href="mobile_binary<?= MOBILE_PARAMETER_SEPARATOR ?>logout" data-role="button" data-theme="r">Deconnexion</a>
					<?php } ?>
					</div>
				</div>
	
			<!-- FRIENDS -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c">
				<h3>Mes amis</h3>
				<?php $i=0; ?>
				<?php foreach ($_SESSION['friends'] as $friend ) { ?>
					<a href="<?= $friend["link"] ?>"><img src="http://graph.facebook.com/<?= $friend["id"] ?>/picture" width="20px" alt="<?= $friend["name"] ?>" /></a>
				<?php $i++; ?>
				<?php } 
			if($i == 0) {
			$socialNetworkConnection =  new SocialNetworkConnection();
				foreach($socialNetworkConnection->getWrappers() as $wrapper) {
					$url = TARGET == "mobile" ? str_replace("www", "m", $wrapper->getLoginUrl()) . "&display=touch" :  $wrapper->getLoginUrl();
					echo "<a href='" . $url . "'>" . $wrapper->getSocialNetworkButton() . "</a>";
					}
				} else { ?>
					<br /><br />
					<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="http://www.mymed.fr" data-send="true" data-width="450" data-show-faces="true"></div>
					<br /><br />
				<?php } ?>
				</div>
	
			<!-- HELP -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c" style="text-align: left;">
				<h3>Aide</h3>
				<h3>
					Bouton Rechercher, au dessus de la carte
				</h3>
				<p>Ce bouton permet la recherche d'itinéraire via les transports publics, nous utilisons l'API 
					<a href="http://www.cityway.fr">Cityway</a> , en cas d'échec vous serez redirigé
					vers un itinéraire Google Maps classique.</p>
				<h3>Points d'intérêts</h3>
				<p>Ils désignent les types d'établissements, d'évênements que vous souhaitez
					afficher sur la carte.</p>
				<h3>Persistence des points d'intérêts</h3>
				<p>Cette préférence permet ou non de conserver les points d'intérêts visités visibles ou non</p>
				<h3>Rayon de recherche</h3>
				<p>La valeur, en mètres, autours de la position actuelle pour laquelle vous
					souhaitez rechercher des points d'intérêts.</p>
				<h3>Types de Trajet Cityway</h3>
				<p>Ces champs permettent de paramétrer votre recherche d'itinéraire.</p>
				<h3>Profil</h3>
				<p>Ce champ donne accès à votre profil myRiviera</p>
				<h3>Mes amis</h3>
				<p>En vous connectant avec Facebook, vous chargerez les positions
					de vos amis (acceptant la géolocalisation), disponibles dans
					la recherche d'itinéraire par le bouton + du champs Arrivée. 
					D'autres fonctionnalités de partage par réseau social sont à venir</p>
			</div>
	
			<!-- ABOUT -->
			<div data-role="collapsible" data-collapsed="true" data-theme="b"
				data-content-theme="c">
				<h3>A propos</h3>
				<h2>myRiviera v1.0 beta</h2>
				<h3>myMed - INTERREG IV - Alcotra</h3>
				<div class="innerContent">
					<img alt="Alcotra" src="system/img/logos/alcotra"
						style="width: 100px;" /> <img alt="Europe"
						src="system/img/logos/europe" style="width: 50px;" /> <img
						alt="Conseil Général 06" src="system/img/logos/cg06"
						style="width: 100px;" /> <img alt="Regine Piemonte"
						src="system/img/logos/regione" style="width: 100px;" /> <img
						alt="Région PACA" src="system/img/logos/PACA" style="width: 100px;" />
					<img alt="Prefecture 06" src="system/img/logos/pref"
						style="width: 70px;" /> <img alt="Inria"
						src="system/img/logos/inria" style="width: 100px;" />
					<p>"Ensemble par-delà les frontières"</p>
				</div>
			</div>
		</div>
	</div>
	
	<?php }
	
}
?>

