<? include("header.php"); ?>
<!-- Header -->
<div data-role="header" data-position="inline">
	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete">Exit</a>
	<h1>MyMemory</h1>
	<a href="?action=ExtendedProfile" data-role="button" data-icon="gear">Profile</a>
</div>
<div>
<p>
Hello <?= $_SESSION['user']->name ?> Welcome to the main page !
</p>


</div>
<? include("footer.php"); ?>