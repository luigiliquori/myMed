<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page" id="GoingBack">
<script type="text/javascript">
	$("#GoingBack").live('pageinit', function() {
		initialize_map();

		var testLatlng = new google.maps.LatLng("43.553532", "7.021980");
		var marker = addMarker(testLatlng, "img/position.png", "Domicile", "Mon chez moi!", google.maps.Animation.DROP, false, "maison");
		marker.setMap(map);

	});
</script>
<!-- GOOGLE MAP -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&libraries=places"></script>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>

<!-- 	<div data-role="header" data-position="inline"> -->
<!-- 		<a href="?action=main" data-rel="back" data-role="button" class="ui-btn-left" data-icon="back" >Back</a> -->
<!-- 		<h1>MyMemory</h1> -->
<!-- 		<a href="?action=ExtendedProfile" data-role="button" data-icon="gear" >Profile</a> -->
<!-- 	</div> -->
	<div data-role="content" data-theme="a">
		<div id="myMap"></div>
		<br />
		<ul data-role="listview" class="ui-listview" data-theme="b" data-inset="true" >
			<li data-icon="home" class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
				<a href="?action=itineraire" class="ui-link-inherit" onclick="goingBack(document.getElementById('address_home').innerHTML)">
				<h3 class="ui-li-heading">Domicile</h3>
				<p class="ui-li-desc" id="address_home" ><?= $_SESSION['ExtendedProfile']->home?></p>
				</a>
			</li>
			<?php
			$i = 0;
			foreach($_SESSION['ExtendedProfile']->callingList as $data) {
				if($data->type == "emergency" ) continue;
				?>
				<li class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
					<a href="?action=itineraire" class="ui-link-inherit" onclick="goingBack(document.getElementById('address<?= $i ?>').innerHTML)">
					<h3 class="ui-li-heading"><?= $data->nickname; ?></h3>
					<?= '<p class="ui-li-desc" id="address'.$i.'" >'. $data->address . '</p>'; ?>
					</a>
				</li>
				
			<?php
			$i++; 	
			}
			?>
			
		</ul>
	</div>
	
	

<input type='hidden' id='userID' value='<?= $_SESSION['user']->id ?>' />
<input type='hidden' id='applicationName' value='myMemory' />
<input type='hidden' id='accessToken' value='<?= $_SESSION['accessToken'] ?>' />

<div data-role="footer" data-id="myFooter" data-position="fixed">
	<div data-role="navbar" data-iconpos="top" >
		<ul>
			<li><a href="?action=main" data-icon="home"><?= _('Homescreen') ?></a></li>
			<li><a href="?action=ExtendedProfile" data-icon="profile" ><?= _('Profile'); ?></a></li>
			<li><a href="#" data-icon="star" ><?= _('Social'); ?></a></li>	
		</ul>
	</div>
</div>
<? include("footer.php"); ?>	
</div>