<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
  			<ul>
  				<li><a href="?action=logout" rel="external" data-icon="back"><?= _("Log Out") ?></a></li>
  				<li><a href="?action=extendedProfile" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
  			</ul>
  		</div>
	</div>
	
	<div data-role="content">
	<br />
	<?= _("<b>Congratulations!</b> Your profile has been sent to myEurope team  
			<a href='mailto:myEuropeDev@gmail.com'>myEuropeDev@gmail.com</a>		
			for validation") ?>
	<br /><br /><br />
	<em>Testers automatically admins: 
	<ul style="list-style-type: none;">
		<li>*@inria.fr</li>
		<li>bredasarah@gmail.com</li>
		<li>luigi.liquori@gmail.com</li>
		<li>myalpmed@gmail.com</li>
	</ul>
	</em>
	<a href="/application/myMed" data-role="button" data-ajax="false" data-icon="back">Exit to mymed ajax=false not working someone help?</a>
	</div>

</div>

<? include("footer.php"); ?>