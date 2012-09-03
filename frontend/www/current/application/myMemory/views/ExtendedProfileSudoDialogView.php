	<div data-role="page" data-theme="a">
		<div data-role="content" data-theme="c" class="ui-overlay-shadow ui-corner-bottom ui-content ui-body-c" role="main">
			<h1>Confirmation requise</h1>
			<p>Afin de prévenir toute modification involontaire de données cruciales, veuillez entrer de nouveau votre mot de passe.</p>
			
			<form id="sudo" action="?action=ExtendedProfile&edit=true" method="post" data-ajax="false" >
	
				<input type="password" name="password" placeholder="Password" />
		
				<input type="submit" name="sudo" value="<?= _("Validate") ?>" data-theme="r" />
		
			</form>
			<a href="?action=ExtendedProfile" data-role="button" data-rel="back" data-theme="a">Annuler</a>
		</div>
	</div>
