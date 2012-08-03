<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
  			<ul>
  				<li><a href="/application/myMed" rel="external" data-icon="back"><?= _("Exit") ?></a></li>
  				<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
  			</ul>
  		</div>
	</div>
	
	<div data-role="content">
	<br />
	<b>Félicitation!</b> Votre profil a été envoyé à l'équipe <a href="mailto:myAlpMed@gmail.com" type="button" data-inline="true" data-mini="true"><?= APPLICATION_NAME ?></a> pour validation.<br />
	<br />
	</div>

</div>

<? include("footer.php"); ?>