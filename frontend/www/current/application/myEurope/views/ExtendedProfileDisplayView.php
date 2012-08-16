<? include("header.php"); ?>

<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			_('Profile'),
			_('Add to contacts'),
			"?action=ExtendedProfile&id=". $this->profile->user ,
			"check") ?>
	<? include("notifications.php"); ?>
</div>

<div data-role="content" >
	
	<?= printProfile($this->profile,
						 $this->id) ?>
	
	
</div>
<? include("footer.php"); ?>