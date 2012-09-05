<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		<div Style="text-align: center; position: relative; top: 15px;"> <?= _("Social Network") ?> </div>
		<span class="ui-title"></span>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content"  class="content">
	
		<img alt="myMed" src="<?= APP_ROOT ?>/img/logo-mymed-250c.png" width="200" />
	
		<form action="?action=login" method="post" data-ajax="false">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="<?= _("Password") ?>"  data-theme="c"/><br />
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" value="<?= _("Log In") ?>" />
		</form>
		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/alcotra.png" />
		<br />
		<i><?= _("Ensemble par-delà les frontières") ?></i>
		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/europe.jpg" />
		
	</div>
	
				
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist"><?= _("Log In") ?></a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid"><?= _("Registration") ?></a></li>
				<li><a href="#about" data-transition="none" data-icon="info"><?= _("About") ?></a></li>
			</ul>
		</div>
	</div>
	
</div>
	
<? require_once("RegisterView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>