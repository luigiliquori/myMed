<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple(null, false); ?>
	<? include("notifications.php"); ?>
	<div data-role="content" >
		<br />
		<?= _("Your partnership offer has been successfully published") ?>
		 <br /><br />
		
		<a href="?action=search&<?= $this->req ?>" type="button" data-inline="true"> <?= _("See similar offers") ?> </a><br />
		
	</div>
</div>

<? include("footer.php"); ?>