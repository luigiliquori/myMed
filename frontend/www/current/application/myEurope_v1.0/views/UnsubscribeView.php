<? include("header.php"); ?>
<div data-role="page">
	<!-- Header -->
	
	<? tabs_simple('Unsubscribe me'); ?>
	<? include("notifications.php"); ?>
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