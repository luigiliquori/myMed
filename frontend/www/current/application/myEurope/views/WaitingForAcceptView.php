<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
  			<ul>
  				<li><a href="/application/myMed" data-ajax="false" data-icon="back"><?= _("Exit") ?></a></li>
  				<li><a href="?action=extendedProfile" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
  			</ul>
  		</div>
	</div>
	
	<div data-role="content">
	<br />
	<?= _("<b>Congratulations!</b> Your profile has been sent to myEurope team  
			<a href='mailto:myEuropeDev@gmail.com'>myEuropeDev+Sarah@gmail.com</a>		
			for validation") ?>
	<br /><br /><br /><br />
	<em>alpha testers automatically admins: *@inria.fr, bredasarah@gmail.com, luigi.liquori@gmail.com, myalpmed@gmail.com</em>
	</div>

</div>

<? include("footer.php"); ?>