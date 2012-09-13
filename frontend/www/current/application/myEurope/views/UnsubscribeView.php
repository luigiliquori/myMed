<? include("header.php"); ?>
<div data-role="page">
	<!-- Header -->
	<div data-role="header" data-theme="c" data-position="fixed">
		<div class="ui-header ui-bar-e" data-mini="true">
	   		<span style="color: #588fbe; font-size: 13px; font-weight: bold; margin-left: 10px;display: inline-block;"><a href="./" rel="external" title="<?= APPLICATION_NAME ?>" data-inline="true" ><h1 style="display: inline-block;margin-top: 0;margin-bottom: 0;"><?= APPLICATION_NAME ?></h1></a> Réseau social transfontalier</span>
	  		<? include("social.php"); ?>
		</div>
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