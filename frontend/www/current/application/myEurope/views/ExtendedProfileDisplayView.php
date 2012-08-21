<? include("header.php"); ?>

<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_2empty(getUser($item->id)) ?>
	<? include("notifications.php"); ?>
</div>

<div data-role="content" >

	<?= printProfile($item->profile) ?>
</div>
<? include("footer.php"); ?>