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
		<a href="?action=main" data-rel="back" data-role="button" class="ui-btn-left" data-icon="back">Back</a>
		<h1>MyMemory</h1>
		<a href="?action=ExtendedProfile" data-role="button" data-icon="gear">Profile</a>
	</div>
	<div data-role="content" data-theme="a">
		<div id="myMap"></div>
		<br />
		<ul data-role="listview" data-inset="true" >
			<li data-icon="home" data-theme="b"><a href="#">Domicile</a></li>
			<?php
			for($i = 0; $i < count($_SESSION['ExtendedProfile']->callingList); $i++ ) {
				$last_arg =  'data-icon="alert" data-theme="e"';
				
				if($i == count($_SESSION['ExtendedProfile']->callingList) -1 ) {
					echo "<li ".$last_arg.">";
				}
				else
					echo "<li>";
				
				echo '<a href="#">';
				echo $_SESSION['ExtendedProfile']->callingList[$i]["name"];
				echo '</a></li>';
				
			}
			?>
			
		</ul>
	</div>
	
<? include("footer.php"); ?>	
</div>