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
	private /*String[]*/ $filterList;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/) {
		parent::__construct("Option");
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
			data-content-theme="c" style="text-align: left;">
			<h3>Points d'interêts</h3>
			<fieldset id="<?= APPLICATION_NAME ?>Filter" data-role="controlgroup"
				data-type="horizontal">	
				<?php foreach ($this->filterList as $filter) { ?>
				<input type="checkbox" name="<?= $filter ?>" id="<?= $filter ?>"
					class="custom" checked="checked" /> <label for="<?= $filter ?>"><?= $filter ?>
				</label><?php } ?>
			</fieldset>
		</div>

		<!-- POIs - radius -->
		<div data-role="collapsible" data-collapsed="true" data-theme="b"
			data-content-theme="c" style="text-align: left;">
			<h3>Rayon de recherche</h3>
			<input type="range" name="slider" id="slider-0" value="500" min="100"
				max="1000" data-theme="b" /> <span style="display: inline;">mètres</span>
		</div>

		<!-- Search Option -->
		<div id="cityway-search" data-role="collapsible" data-collapsed="true"
			data-theme="b" data-content-theme="c">
			<h3>Type de Trajet Cityway</h3>
			<fieldset data-role="controlgroup" data-type="horizontal">
				<input type="radio" name="radio-choice" id="radio-choice1" value="fastest" checked="checked" />
				<label for="radio-choice1">Le	plus rapide</label>
				<input type="radio" name="radio-choice" id="radio-choice2" value="lessChanges" />
				<label for="radio-choice2">Le moins de changements</label>
			</fieldset>
			<fieldset data-role="controlgroup" data-type="horizontal">
				<input type="checkbox" name="checkbox" id="checkbox0"	checked="checked" /><label for="checkbox0">Bus</label>
				<input type="checkbox" name="checkbox" id="checkbox1" checked="checked" /><label	for="checkbox1">Metro</label>
				<input type="checkbox" name="checkbox" id="checkbox2" checked="checked" /><label for="checkbox2">Car</label>
				<input type="checkbox" name="checkbox" id="checkbox3" checked="checked" /><label for="checkbox3">Train</label>
				<input type="checkbox" name="checkbox" id="checkbox4"	checked="checked" /><label for="checkbox4">Tram</label>
				<input type="checkbox" name="checkbox" id="checkbox5" checked="checked" /><label for="checkbox5">Ter</label>
				<input type="checkbox" name="checkbox" id="checkbox6" checked="checked" /><label for="checkbox6">Boat</label>
				<input type="checkbox" name="checkbox" id="checkbox13" checked="checked" /><label for="checkbox13">Avion</label>
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
				Bouton Rechercher au dessus de la carte
			</h3>
			<p>Ce bouton permet la recherche d'itinéraire, notre méthode de
				calcul intègre l'API Cityway, en cas d'échec vous serez redirigé
				vers un itinéraire Google Maps classique.</p>
			<h3>Points d'intérêts</h3>
			<p>Ils désignent les types d'établissements que vous souhaitez
				afficher.</p>
			<h3>Rayon de recherche</h3>
			<p>La valeur autours de la position actuelle pour laquelle vous
				souhaitez les rechercher.</p>
			<h3>Types de Trajet Cityway</h3>
			<p>Ces champs permettent de paramétrer votre recherche d'itinéraire.</p>
			<h3>Profil</h3>
			<p>Ce champ donne accès à votre profil myRiviera</p>
			<h3>Mes amis</h3>
			<p>En vous connectant avec Facebook, vous chargerez les informations
				relatives à vos amis, leurs positions par exemples, disponibles dans
				la recherche d'itinéraire par le bouton + du champs Arrivée.</p>
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

