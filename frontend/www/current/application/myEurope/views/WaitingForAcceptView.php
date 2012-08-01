<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
  			<ul>
  				<li><a href="?action=logout" rel="external" data-icon="back"><?= _("Exit") ?></a></li>
  				<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
  			</ul>
  		</div>
	</div>
	
	<div data-role="content">
	<br />
	<b>Félicitation!</b> Votre profil est en attente de validation.<br />
	Contactez-nous à cet <a href="mailto:myAlpMed@gmail.com" type="button" data-inline="true" data-mini="true"> Email</a>, pour toute demande d'information.
	</div>

</div>

<? include("footer.php"); ?>