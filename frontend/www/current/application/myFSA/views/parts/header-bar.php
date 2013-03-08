<div data-role="header" data-theme="b" data-position="fixed">
	
	<h1 style="color: white;"><?= APPLICATION_NAME ?></h1>
	
	<? if (isset($_SESSION["launchpad"])) { ?>
		<a href="/application/myMed" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="fahome" data-iconpos="notext" data-theme="e">myMed</a>
	<? } else { ?>
  		<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="signout" data-iconpos="notext">Deconnexion</a>
  	<? } ?>
	
	<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
		<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/102256186136122033301/posts" title="myFSA on Google+"></a>
		<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/MyFSA/122386814581009" title="myFSA on Facebook"></a>
		<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/myFSA1" title="myFSA on Twitter"></a>
	</span>
	<? include_once "notifications.php"; ?>
	
</div>