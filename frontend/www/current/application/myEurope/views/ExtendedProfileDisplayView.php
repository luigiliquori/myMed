<? include("header.php"); ?>




<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			"Profil",
			"Ajouter aux contacts",
			"?action=ExtendedProfile&add=<?= $this->profile->user ?>",
			"check") ?>
	<? include("notifications.php")?>
</div>

<div data-role="content" >
	
	<? if (isset($this->profile)) :?>
		Rôle: <?= $this->profile->role ?><br />
		activité: <?= $this->profile->activity ?><br />
		Email: <?= $this->profile->email ?><br />
		Adresse: <?= $this->profile->address ?><br />
		<br /><br />
		Réputation: 
		<? for($i=20; $i<=100; $i+=20) : ?>
			<a data-theme="<?= ($this->reputation['rep'] >= $i)?'e':'c' ?>" data-role="button" data-iconpos="notext" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"></a>
		<? endfor ?>
		(<?= $this->reputation['plus'] ?>+ <?= $this->reputation['minus'] ?>-)
		
	<? endif ?>
	
	
</div>
<? include("footer.php"); ?>