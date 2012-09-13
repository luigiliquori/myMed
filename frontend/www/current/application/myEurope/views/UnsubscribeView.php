<? include("header.php"); ?>
<div data-role="page">
	<!-- Header -->
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_simple("Unsubscription", $_SESSION['user']->name) ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content" >
	
		<br />
		<? if (!empty($this->res)) :?>
			<h1 style="font-family: Trebuchet MS;"><?= _("Unsubscribed") ?></h1>
		<? else :?>
			<h1 style="font-family: Trebuchet MS;"><?= _("You were not subscribed to these keywords") ?></h1>	
		<? endif ?>
		
	</div>
</div>
<? include("footer.php"); ?>