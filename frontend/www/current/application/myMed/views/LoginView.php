<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<?php 
function tab_bar_login($activeTab) {
	tabs($activeTab, array(
			array("#login", "Connexion", "signin"),
			array("#register", "Inscription", "th-list"),
			array("#about", "A propos", "info-sign")
	));
}
?>

<div data-role="page" id="login">

	<? tab_bar_login("#login"); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content"  class="content">

		<img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" width="200" />
		<form action="?action=login" method="post" data-ajax="false">
			<input type="hidden" name="signin" value="1" />
			<input type="text" name="login" id="login" placeholder="email"  data-theme="c"/>
		    <input type="password" name="password" id="password" data-inline="true" placeholder="<?= _("Password") ?>"  data-theme="c"/>
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" data-icon="signin" value="Connexion" />
			
		</form>
		
		
		<div data-role="collapsible" data-mini="true" data-collapsed="true" data-collapsed="false" data-content-theme="c" data-inline="true" style="text-align: center;width: 260px; margin: auto;">
			<h3><?= _("Options") ?></h3>
			
			<h4><?= _("Connection from other accounts") ?></h4>
			
			(<a href="http://openid.net/">OpenID</a>) <a  title="log in with Google openID" 
			onclick="$('#openIdProvider').val('https://www.google.com/accounts/o8/id'); $('#openIdForm').submit();" rel="external" style="background-position: -1px -1px" class="oauth_small_btn"></a>
			 <a href="#oidFormPopup" data-rel="popup" data-role="none" >plus</a>
			
			
			<div data-role="popup" id="oidFormPopup" class="ui-content" data-theme="d">
				<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<form id="openIdForm" action="/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php" data-ajax="false">
					Url OpenID:
					<input id="openIdProvider" type="text" style="width:280px;display:inline-block;" data-mini="true" name="openid_identifier" data-inline="true" value="https://www.google.com/accounts/o8/id" placeholder="Open ID url" />
					<div style="display:inline-block;vertical-align: middle;">
						<input type="submit" value="OK" title="log in with openid" data-mini="true" data-inline="true"/>
					</div>
				</form>
			</div>
			<br />
			
			(<a href="http://oauth.net/2/">OAuth</a>) <a  title="log in with Google Oauth" 
			href="/lib/socialNetworkAPIs/google/examples/simple/oauth_try.php" rel="external" style="background-position: -1px -1px" class="oauth_small_btn"></a>
			 <a  title="log in with Facebook Oauth" 
			href="/lib/socialNetworkAPIs/facebook/examples/oauth_try.php" rel="external" style="background-position: -1px -105px" class="oauth_small_btn"></a>
			
		</div>



		<br /><br />
		<img alt="Alcotra" src="/system/img/logos/alcotra.png" />
		<br />
		<i>"Ensemble par-delà les frontières"</i>
		<br /><br />
		<img alt="Alcotra" src="/system/img/logos/europe.jpg" />
		
	</div>
	

	
</div>
	
<div data-role="page" id="register">
	

	<? tab_bar_login("#register") ?>
	<? include("notifications.php"); ?>


	<div data-role="content">
	
		<!--  Register form -->
		<form action="?action=register" method="post" data-ajax="false">
		
				<label for="prenom">Prénom / Activité commerciale : </label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom">Nom : </label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" >eMail : </label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" >Mot de passe : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" >Confirmation : </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
				<span style="position: relative; left: 50px;">
					J'accepte les 
					<a href="application/myMed/doc/CGU_fr.pdf" rel="external">conditions d'utilisation</a> / 
					I accept 
					<a href="application/myMed/doc/CGU_en.pdf" rel="external">the general terms and conditions</a>
				</span><br />
				
				<center>
					<input type="submit" data-role="button" data-theme="b" data-inline="true" value="Valider" />
				</center>
		
		</form>
	</div>

	
</div>

<div data-role="page" id="about" >	

	<? tab_bar_login("#about"); ?>
	<? include("notifications.php"); ?>


	<div data-role="content" class="content">
	
		<img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" />
		<br />
		<h3> Réseau Social Transfrontalier </h3>
		<br />
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
			<li>
			<p>Le projet myMed est né d’une double constatation: l’existence d’un énorme potentiel de développement des activités économiques de la zone transfrontalière, objet de l’action Alcotra, et le manque criant d’infrastructures techniquement avancées en permettant un développement harmonieux. La proposition myMed est née d’une collaboration existante depuis plus de 15 ans entre l’Institut National de Recherche en Informatique et en Automatique (INRIA) de Sophia Antipolis et l’Ecole Polytechnique de Turin, auxquels viennent s’ajouter deux autres partenaires, l’Université de Turin et l’Université du Piémont Oriental.
			</p>
			</li>
		</ul>
		
		<p>
		<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank">Plus d'informations</a>
		</p>
		<br />
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>

</div>

<? require_once("footer.php"); ?>