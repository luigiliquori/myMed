<? include("header.php"); ?>

<div data-role="page">
	
	<? tabs_simple(null, false); ?>
	<? include("notifications.php"); ?>
	<div data-role="content" style="text-align: center;">
	<br />
	<?= _("<b>Congratulations!</b> Your profile has been sent to myEurope team  
			<a href='mailto:myEuropeStaff@gmail.com'>myEuropeStaff@gmail.com</a>		
			for validation") ?>
	<br /><br /><br />
	<a href="/application/myMed" data-role="button" data-inline=true rel="external" data-icon="signout" data-theme="r">myMed</a>
	</div>

</div>

<? include("footer.php"); ?>