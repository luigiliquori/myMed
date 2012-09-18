<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		<img alt="title" src="img/icon.png" height="40" Style="position: absolute;" />
		<h1><?= APPLICATION_NAME ?></h1>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content"  class="content">
	
		<form action="?action=login" method="post" data-ajax="false" style="position: relative; top: 100px; height: 400px;">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="<?= _("Password") ?>"  data-theme="c"/><br />
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Connexion") ?>" />
		</form>
		
		<div style="position: relative; top: -100px;">
			<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/alcotra.png" />
			<br />
			<i><?= _("Ensemble par-delà les frontières") ?></i>
			<br /><br />
			<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/europe.jpg" />
		</div>
		
		<br/><br/>
		
	</div>
	
				
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist"><?= _("Connexion") ?></a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid"><?= _("Inscription") ?></a></li>
				<li><a href="#about" data-transition="none" data-icon="info"><?= _("A propos") ?></a></li>
			</ul>
		</div>
	</div>
	
</div>
	
<? require_once("RegisterView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>