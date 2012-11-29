<? include("header.php"); ?>

<?php 

// ---------  dead view -----------

?>

<div data-role="page">
	
	<? tabs_simple(null, false); ?>
	<? include("notifications.php"); ?>
	<div data-role="content" style="text-align: center;">
	<br><br>
	<?= _("<b>Congratulations!</b> Your profile has been sent to myEurope team  
			<a href='mailto:myEuropeStaff@gmail.com'>myEuropeStaff@gmail.com</a>		
			for validation") ?>
	<br /><br /><br />
	<a href="/europe" data-role="button" data-inline=true  data-icon="back"><?= APPLICATION_NAME ?></a>
	</div>

</div>

<? include("footer.php"); ?>