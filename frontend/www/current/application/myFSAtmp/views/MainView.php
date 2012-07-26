<? include("header.php"); ?>
<div data-role="page" data-theme="a">
<div data-role="header" data-theme="a">
<a data-rel="back" data-role="button"  data-icon="back">Back</a>
<h3>myFSA</h3>
</div>
 <div data-role="content" style="padding: 15px" data-ajax="false">
	<a data-ajax="false" href="?action=Localisate" type="button" data-transition="slide" >Localisate</a>
	<a data-role="button" data-transition="fade" href="?action=Publish">Publish</a>
	<form action="index.php?action=publish" method="POST" data-ajax="false">
	<input type="submit" name="method" value="Search" />
	</form>
	
</div>
<? include("footer.php"); ?>
</div>
