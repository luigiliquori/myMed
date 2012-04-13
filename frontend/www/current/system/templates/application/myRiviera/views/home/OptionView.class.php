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

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Option");
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
			<a href="#Map" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
	<!-- CONTENT -->
	<div data-role="content" Style="font-size: 10pt; width: 90%; margin-left: auto; margin-right: auto;">
		<!-- UPDATE POIs -->
		<div data-role="collapsible-set">
	
			<!-- Profile -->
			<div data-role="collapsible" data-collapsed="false" data-theme="d" data-content-theme="c">
				<h3>Profil</h3>
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
					<a href="#inscription" data-role="button" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a>
					<a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a>
				</div>
			</div>
	
			<!-- FRIENDS -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
				data-content-theme="c">
				<h3>Réseau social</h3>
				<?php $i=0; ?>
				<?php foreach ($_SESSION['friends'] as $friend ) { ?>
					<a href="<?= $friend["link"] ?>"><img src="http://graph.facebook.com/<?= $friend["id"] ?>/picture" width="20px" alt="<?= $friend["name"] ?>" /></a>
					<?php $i++; ?>
				<?php } 
				if($i == 0) { 
// 					$socialNetworkConnection =  new SocialNetworkConnection();
// 					foreach($socialNetworkConnection->getWrappers() as $wrapper) {
// 						$url = TARGET == "mobile" ? str_replace("www", "m", $wrapper->getLoginUrl()) . "&display=touch" :  $wrapper->getLoginUrl();
// 						echo "<a href='" . $url . "' onClick='showLoadingBar(\"redirecton en cours...\")'>" . $wrapper->getSocialNetworkButton() . "</a>";
// 					}
				?>
				<!-- CONNECTION FACEBOOK -->
		 	    <div id="fb-root"></div>
			    <script>
			        window.fbAsyncInit = function() {
			          FB.init({
			            appId      : '<?= Facebook_APP_ID ?>',
			            status     : true, 
			            cookie     : true,
			            xfbml      : true,
			            oauth      : true,
			          });
			          FB.Event.subscribe('auth.login', function(response) {
			              window.location.reload();
			            });
			        };
			        (function(d){
			           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
			           js = d.createElement('script'); js.id = id; js.async = true;
			           js.src = "//connect.facebook.net/en_US/all.js";
			           d.getElementsByTagName('head')[0].appendChild(js);
			         }(document));
			    </script>
			    <div class="fb-login-button" scope="email,read_stream">Facebook</div>
			    <!-- END CONNECTION FACEBOOK -->
				<?php } else { ?>
					<!-- LIKE BUTTON -->
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
			
			<!-- COMMENT -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
				data-content-theme="c">
				<h3>Commentaires</h3>
				<p>Un commentaire, une idée, une demande? Laissez nous un commentaire!</p>
				<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>Admin" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="3" />
					
					<!-- KEYWORD -->
					<input type="hidden" name="keyword" value="myRivieraTest"/>
					<?php $keywordBean = new MDataBean("keyword", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keywordBean)); ?>">
				
					<!-- EMAIL -->
					<input type="hidden" name="email" value="<?= $_SESSION['user']->email ?>"/>
					<?php $keywordBean = new MDataBean("email", null, TEXT); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($keywordBean)); ?>">
				
					<!-- TEXT -->
					<textarea name="text" rows="" cols=""></textarea>
					<?php $text = new MDataBean("text", null, TEXT); ?>
					<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($text)); ?>">
					<br />
					
					<a href="#" data-role="button" onclick="alert('Merci pour votre contribution'); document.<?= APPLICATION_NAME ?>PublishForm.submit()" >Publier</a>
				</form>
			</div>
	
			<!-- HELP -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
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
				<p>Cette préférence permet ou non de conserver les points d'intérêts visités visibles.</p>
				<h3>Rayon de recherche</h3>
				<p>La valeur, en mètres, autours de la position actuelle pour laquelle vous
					souhaitez rechercher des points d'intérêts.</p>
				<h3>Types de Trajet Cityway</h3>
				<p>Ces champs permettent de paramétrer votre recherche d'itinéraire.</p>
				<h3>Profil</h3>
				<p>Ce champ donne accès à votre profil myRiviera.</p>
				<h3>Réseau social</h3>
				<p>En vous connectant avec Facebook, vous chargerez les positions
					de vos amis (acceptant la géolocalisation), disponibles dans
					la recherche d'itinéraire par le bouton + du champs Arrivée.</p>
			</div>
	
			<!-- ABOUT -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
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

