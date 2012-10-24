<? include("header.php"); ?>


<div data-role="page" data-theme="b">
<? include("header-bar.php"); ?>


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