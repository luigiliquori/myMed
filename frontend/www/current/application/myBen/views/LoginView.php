<!--<? //include("header.php"); ?>

<div data-role="page" id="login">

	<? //include("header-bar.php") ?>-->
	
	<div data-role="content" class="content">
		<img alt="myBenevolat" src="img/icon.png" width="110">
		<!-- Login form -->
		<form  data-role="content" action="<?= url("login:doLogin") ?>" method="post" data-ajax="false" >
	
			<input type="text" name="login" placeholder="Login (email)" />
			<input type="password" name="password" placeholder="Password" />
	
			<!-- <div class="ui-grid-a">
				<div class="ui-block-a">
					<input type="submit" value="<?= _("Se connecter") ?>" data-theme="b" />
				</div>
				<div class="ui-block-b">
					<a data-theme="e" data-role="button" data-ajax="false" href="<?= url("extendedProfile:create") ?>">
						<?= _("Créer un compte") ?>
					</a>
				</div>
			</div>-->
			<input type="submit" value="<?= _("Connexion") ?>" data-role="button" data-inline="true" data-theme="b" data-icon="signin"/>
		</form>
	</div>
<!-- </div>-->

<!--<? //include("footer.php"); ?>-->