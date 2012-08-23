<? include("header.php"); ?>
<div data-role="page">
	<!-- Header -->
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2empty(prettyprintUser($_SESSION['user']->id)) ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content" >
	
		<br />
		<? if (!empty($this->res)) :?>
			<h1 style="font-family: Trebuchet MS;"><?= _("Unsubscribed") ?></h1>
		<? else :?>
			<h1 style="font-family: Trebuchet MS;">...</h1>	
		<? endif ?>
		
	</div>
</div>
<? include("footer.php"); ?>