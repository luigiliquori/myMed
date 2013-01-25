<? include("notifications.php")?>

<div data-role="page" id="GoingBack">
	
	
	<!-- Header -->
	<div data-role="header" data-position="inline">
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<h1><?= _("GoingBack"); ?></h1>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>



	<div data-role="content" data-theme="a">
	
		<div class="description-box">
		<?= _("MyMemory_GoingBackDesc") ?>
		</div>
	
	
	
		<ul data-role="listview" class="ui-listview" data-theme="b" data-inset="true" >
			<li data-icon="home" class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
				<a href="?action=itineraire&amp;address=<?= $_SESSION['ExtendedProfile']->home?>" class="ui-link-inherit" onclick="goingBack(document.getElementById('address_home').innerHTML)">
				<h3 class="ui-li-heading"><?= _("Domicile"); ?></h3>
				<p class="ui-li-desc" id="address_home" ><?= $_SESSION['ExtendedProfile']->home?></p>
				</a>
			</li>
			<?php
			$i = 0;
			foreach($_SESSION['ExtendedProfile']->callingList as $data) {
				if($data->type == "emergency" ) continue;
				?>
				<li class="ui-btn ui-btn-icon-right ul-li-has-arrow ui-li" style="padding-bottom:1em;">
					<a href="?action=itineraire&amp;address=<?= $data->address; ?>" class="ui-link-inherit" data-ajax="false">
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

	


</div>