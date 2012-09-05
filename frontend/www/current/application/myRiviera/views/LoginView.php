<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		<div Style="text-align: center; position: relative; top: 15px;"> Réseau Social Transfrontalier </div>
		<span class="ui-title"></span>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content"  class="content">
	
		<div style="position: absolute; left: 50%; top: 90px;">
			<img alt="title" src="img/icon.png" height="50"  style="position: relative; margin-left: -150px;" />
		</div>
		<div style="position: absolute; left: 50%; top: 73px;">
			<h1 style="position: relative; left: -40px;"><?= APPLICATION_NAME ?></h1>
		</div>
	
		<form action="?action=login" method="post" data-ajax="false" style="position: relative; top: 100px; height: 400px;">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="Mot de passe"  data-theme="c"/><br />
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" value="Connexion" />
		</form>

		<div style="position: relative; top: -100px;">
			<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/alcotra.png" />
			<br />
			<i>"Ensemble par-delà les frontières"</i>
			<br /><br />
			<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/europe.jpg" />
		</div>
		
	</div>
	
				
	<div data-role="footer" data-position="fixed" data-theme="a">
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