<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page" id="GoingBack">
<script type="text/javascript">
	$("#GoingBack").live('pageinit', function() {
		initialize_map();

		var testLatlng = new google.maps.LatLng("43.553532", "7.021980");

	});
</script>


	<div data-role="header" data-position="inline">
		<a href="?action=main" data-rel="back" data-role="button" class="ui-btn-left" data-icon="back" >Back</a>
		<h1>MyMemory</h1>
		<a href="?action=ExtendedProfile" data-role="button" data-icon="gear" >Profile</a>
	</div>
	<div data-role="content" data-theme="a">
		<div id="myMap"></div>
		<br />
		<ul data-role="listview" class="ui-listview" data-theme="b" data-inset="true" >
			<li data-icon="home" class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
				<a href="#" class="ui-link-inherit">
				<h3 class="ui-li-heading">Domicile</h3>
				<p class="ui-li-desc"><?= $_SESSION['ExtendedProfile']->home?></p>
				</a>
			</li>
			<?php
			foreach($_SESSION['ExtendedProfile']->callingList as $data) {
				if($data['name'] == "Emergency" ) continue;
				?>
				<li class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
					<a href="#">
					<h3 class="ui-li-heading"><?= $data["name"]; ?></h3>
					<p class="ui-li-desc"><?= $data['address']?></p>
					</a>
				</li>
				
			<?php 	
			}
			?>
			
		</ul>
	</div>
	
<? include("footer.php"); ?>	
</div>