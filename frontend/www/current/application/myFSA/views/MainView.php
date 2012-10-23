<? include("header.php"); ?>


<div data-role="page" data-theme="b">
<div data-role="header" data-theme="b" data-position="fixed">
	
	<h1><?= APPLICATION_NAME ?></h1>
	
	<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="power" data-iconpos="notext">Deconnexion</a>
	
	<? include("notifications.php")?>
	
</div>


 	<div data-role="content" style="padding: 15px" data-ajax="false">
	<a data-ajax="false" href="controllers/LocalisationController.php" type="button" data-transition="slide" >Localiser</a>
	<a data-role="button" data-transition="fade" href="?action=Publish"> Publier</a>
	<form action="index.php?action=publish" method="POST" data-ajax="false">
	<input type="submit" name="method" value="Rechercher" />
	</form>
	</div>
	
<? include("footer.php"); ?>
</div>
</body>