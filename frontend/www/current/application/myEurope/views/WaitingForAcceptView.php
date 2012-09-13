<? include("header.php"); ?>

<div data-role="page">
	
	<div data-role="header" data-theme="c" data-position="fixed">
		<div class="ui-header ui-bar-e" data-mini="true">
	   		<span style="color: #588fbe; font-size: 13px; font-weight: bold; margin-left: 10px;display: inline-block;"><a href="./" rel="external" title="<?= APPLICATION_NAME ?>" data-inline="true" ><h1 style="display: inline-block;margin-top: 0;margin-bottom: 0;"><?= APPLICATION_NAME ?></h1></a> Réseau social transfontalier</span>
	  		<? include("social.php"); ?>
		</div>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content" style="text-align: center;">
	<br />
	<?= _("<b>Congratulations!</b> Your profile has been sent to myEurope team  
			<a href='mailto:myEuropeStaff@gmail.com'>myEuropeStaff@gmail.com</a>		
			for validation") ?>
	<br /><br /><br />
	<a href="/application/myMed" data-role="button" data-inline=true rel="external" data-icon="off">myMed</a>
	</div>

</div>

<? include("footer.php"); ?>