<? 
//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="switch">

	<? include("header-bar.php") ?>
	
	<div data-role="content">

		<p>
			Bonjour,<br/>
			<br/>
			C'est la première fois que vous venez sur MyBénévolat.<br/>
			Merci de renseigner votre profil.<br/>
			<br/>
			Vous êtes ?<br/>
			<br/>
			<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
				href="<?= url('extendedProfile:create', array("type" => BENEVOLE)) ?>">
				Un bénévole
			</a>
			<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
				href="<?= url('extendedProfile:create', array("type" => ASSOCIATION))?>">
				Une association
			</a>		
		</p>
		
	</div>
</div>

<? include("footer.php"); ?>