<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		
		<h1>Bienvenue</h1>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content"  class="content">
	
		<img alt="myMed" src="img/logo-mymed-250c.png" />
		<h3> Réseau Social Transfrontalier </h3>
	
		<form action="?action=login" method="post" data-ajax="false">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="Mot de passe"  data-theme="c"/><br />
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" value="Connexion" />
		</form>
		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_ROOT ?>/system/img/logos/alcotra" />
		<br />
		<i>"Ensemble par-delà les frontières"</i>
		
	</div>
	
				
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	
</div>
	
<? require_once("RegisterView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>