<? include("header.php"); ?>
<? include("notifications.php")?>




<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			"Profil",
			"Modifer",
			"?action=ExtendedProfile&edit=false",
			"gear") ?>
</div>

<div data-role="content" >
	
	<? if (isset($this->profile)) :?>
		Rôle: <?= $this->profile->role ?><br />
		activité: <?= $this->profile->activity ?><br />
		Email: <?= $this->profile->email ?><br />
		Adresse: <?= $this->profile->address ?><br />
	<? endif ?>
	
	
</div>
<? include("footer.php"); ?>