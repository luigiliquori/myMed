<? 
//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="switch">

	<? include("header-bar.php") ?>
	<? tab_bar_main("?action=extendedProfile"); ?>
	
	<div data-role="content">

		<p>
			<?= _("Bonjour,") ?><br/>
			<br/>
			<?= _("C'est la première fois que vous venez sur MyBénévolat.") ?><br/>
			<?= _("Merci de renseigner votre profil.") ?><br/>
			<br/>
		    <?= _("Vous êtes ?") ?><br/>
			<br/>
			<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
				href="<?= url('extendedProfile:create', array("type" => BENEVOLE)) ?>">
				<?= _("Un bénévole") ?>
			</a>
			<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
				href="<?= url('extendedProfile:create', array("type" => ASSOCIATION))?>">
                <?= _("Une association") ?>
			</a>		
		</p>
		
	</div>
</div>

<? include("footer.php"); ?>
