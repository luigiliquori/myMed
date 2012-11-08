<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple('Published', false); ?>
	<? include("notifications.php"); ?>
	<div data-role="content" style="text-align: center;">
		<span style="height: 50px;"></span>
		<br />
		<?= _("Your partnership offer has been successfully published") ?>
		 <br /><br />
		
		<a href="?action=search&<?= $this->req ?>" type="button" data-inline="true"> <?= _("See similar offers") ?> </a><br />
		
	</div>
</div>

<? include("footer.php"); ?>