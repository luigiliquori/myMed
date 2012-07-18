<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		
		<a href="http://www-sop.inria.fr/lognet/MYMED/" target="blank" data-icon="info" data-iconpos="right" class="ui-btn-right">A propos</a>
		<span class="ui-title" />
		<? include("notifications.php")?>
		
	</div>
	
	<div data-role="content"  class="content">
	
		<img alt="myMed" src="img/logo-mymed-250c.png" />
	
		<form action="?action=login" method="post" data-ajax="false">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="Mot de passe"  data-theme="c"/><br />
		    <div data-role="controlgroup" data-type="horizontal">
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" value="Connexion" />
 		    <a href="#register" data-role="button" data-inline="true">Inscription</a>
 		    </div>
		</form>
		
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>
	
</div>
	
<? include("RegisterView.php"); ?>

<? include("footer.php"); ?>