<div data-role="content" class="content">
		<img alt="myBenevolat" src="img/icon.png" width="110">
		<!-- Login form -->
		<form  data-role="content" action="<?= url("login:doLogin") ?>" method="post" data-ajax="false" >
	
			<input type="text" name="login" placeholder="Login (email)" />
			<input type="password" name="password" placeholder="Password" />
	
			<input type="submit" value="<?= _("Connexion") ?>" data-role="button" data-inline="true" data-theme="b" data-icon="signin"/>
		</form>
	</div>