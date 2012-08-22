<? include("header.php"); ?>

<div data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2empty(getUser($this->id)) ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content" >
	
		<?= printProfile($this->profile, $this->id) ?>
	</div>
</div>
<? include("footer.php"); ?>