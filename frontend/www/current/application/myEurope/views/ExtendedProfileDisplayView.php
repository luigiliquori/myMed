<? include("header.php"); ?>
<? include("notifications.php")?>

<? $this->redirectTo("main", null, "#profile"); ?>



<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			"Profil",
			"Modifer",
			"?action=ExtendedProfile&edit=false",
			"gear") ?>
</div>

<div data-role="content" >
	
	
	Rôle: <?= $_SESSION['myEuropeProfile']->role ?>
	
</div>
<? include("footer.php"); ?>