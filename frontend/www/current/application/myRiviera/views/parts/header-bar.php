<div data-role="header" data-theme="b">
	
	<h1><?= APPLICATION_NAME ?></h1>
	
  	<? if (isset($_SESSION["launchpad"])) { ?>
		<a href="/application/myMed" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="fahome" data-iconpos="notext" data-theme="e">myMed</a>
	<? } else { ?>
  		<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="signout" data-iconpos="notext">Deconnexion</a>
  	<? } ?>
	
	<? include("notifications.php")?>
	
</div>