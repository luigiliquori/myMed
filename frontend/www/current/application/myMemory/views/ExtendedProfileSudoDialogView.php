	<div data-role="page" data-theme="a">
		<div data-role="content" data-theme="c" class="ui-overlay-shadow ui-corner-bottom ui-content ui-body-c" role="main">
			<h1><?= _("ConfirmationNeeded"); ?></h1>
			<p><?= _("MyMemory_SudoDialog") ?></p>
			
			<form id="sudo" action="?action=ExtendedProfile&edit=true" method="post" data-ajax="false" >
	
				<input type="password" name="password" placeholder="Password" tabindex="0"/>
		
				<input type="submit" name="sudo" value="<?= _("Validate") ?>" data-theme="g" tabindex="1"/>
		
			</form>
			<a href="?action=ExtendedProfile" data-role="button" data-rel="back" data-theme="r" tabindex="2"><?= _("Cancel"); ?></a>
		</div>
	</div>
