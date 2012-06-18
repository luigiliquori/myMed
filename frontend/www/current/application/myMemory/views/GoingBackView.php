<? include("header.php"); ?>
<? include("notifications.php")?>
<script type="text/javascript">

</script>


<div data-role="page" id="GoingBack">
	<div data-role="header" data-position="inline">
		<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete">Exit</a>
		<h1>MyMemory</h1>
		<a href="?action=ExtendedProfile" data-role="button" data-icon="gear">Profile</a>
	</div>
	<div data-role="content" data-theme="a">
		<div id="map_canvas" style="height:200px;"></div>
		<br />
		<ul data-role="listview" data-inset="true" >
			<li data-icon="home" data-theme="b"><a href="#">Domicile</a></li>
			<li><a href="#"><?= $_SESSION['ExtendedProfile']->callingList[0]["name"] ?></a></li>
			<li><a href="#"><?= $_SESSION['ExtendedProfile']->callingList[1]["name"] ?></a></li>
			<li><a href="#"><?= $_SESSION['ExtendedProfile']->callingList[2]["name"] ?></a></li>
			<li data-icon="alert" data-theme="e"><a href="#"><?= $_SESSION['ExtendedProfile']->callingList[3]["name"] ?></a></li>
		</ul>
	</div>
</div>
<? include("footer.php"); ?>