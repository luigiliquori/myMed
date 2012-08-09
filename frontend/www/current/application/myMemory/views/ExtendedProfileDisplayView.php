<? include("header.php"); ?>
<? include("notifications.php")?>
<!-- Header -->
<div data-role="header" data-position="inline">
 	<!-- <a href="?action=main" data-role="button"  data-icon="back">Back</a>-->
 	<h1></h1>
 	<a href="?action=ExtendedProfile&edit=false" data-rel="dialog" data-transition="pop" data-role="button" data-theme="b" data-icon="gear" class="ui-btn-right">Edit</a>
</div>

<div data-role="content" data-theme="a">
	
	<ul data-role="listview" class="ui-listview">
		<li class="ui-li ui-li-static ui-body-a ui-li-has-thumb">
			<?php if ($_SESSION['user']->profilePicture) {?>
			<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ui-li-thumb"/>
			<?php }?>
			<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
			<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
			<p class="ui-li-desc"><?=$_SESSION['ExtendedProfile']->home?></p>
			<p class="ui-li-desc">Level of the disease : <strong><?= _('DiseaseLevel'.$_SESSION['ExtendedProfile']->diseaseLevel)?></strong></p>
		</li>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Caregiver : <?= $_SESSION['ExtendedProfile']->careGiver->nickname?></h3>
				<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a">Phone :</div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $_SESSION['ExtendedProfile']->careGiver->phone?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->careGiver->phone?></a></div>
					<div class="mymem-profile-block-a">Address :</div>
					<div class="mymem-profile-block-b" data-role="button" style="text-align:center;" data-theme="b"><?= $_SESSION['ExtendedProfile']->careGiver->address?></div>
					<div class="mymem-profile-block-a">E-mail :</div>
					<div class="mymem-profile-block-b"><a href="mailto:<?= $_SESSION['ExtendedProfile']->careGiver->email?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->careGiver->email?></a></div>
				</div>
		</li>
		
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Doctor : <?= $_SESSION['ExtendedProfile']->doctor->nickname?></h3>
			<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a">Phone :</div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $_SESSION['ExtendedProfile']->doctor->phone?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->doctor->phone?></a></div>
					<div class="mymem-profile-block-a">Address :</div>
					<div class="mymem-profile-block-b" data-role="button" style="text-align:center;" data-theme="b"><?= $_SESSION['ExtendedProfile']->doctor->address?></div>
					<div class="mymem-profile-block-a">E-mail :</div>
					<div class="mymem-profile-block-b"><a href="mailto:<?= $_SESSION['ExtendedProfile']->doctor->email?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->doctor->email?></a></div>
			</div>
		</li>
		
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Calling List</h3>
			
			<?php foreach($_SESSION['ExtendedProfile']->callingList as $callingSlot=>$data) {?>
			
			<div data-role="collapsible">
				<h3><?= _('callingslot'.$callingSlot);?></h3>
				<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a"><h3><?= $data->nickname?></h3></div>
					<div class="mymem-profile-block-b"></div>
					<div class="mymem-profile-block-a">Phone :</div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $data->phone?>" data-role="button" data-theme="b"><?= $data->phone?></a></div>
					
					<div class="mymem-profile-block-a">E-mail :</div>
					<div class="mymem-profile-block-b"><a href="mail:<?= $data->email?>" data-role="button" data-theme="b"><?= $data->email?></a></div>
					
					<?php if (!empty($data->address)) {?> 
					<div class="mymem-profile-block-a">Address :</div>
					<div class="mymem-profile-block-b" style="text-align:center;" data-role="button" data-theme="b"><?= $data->address?></div>
					<?php }?>
				</div>
			</div>
			<?php }?>			
		</li>
	</ul>
</div>

<div data-role="footer" data-id="myFooter" data-position="fixed">
	<div data-role="navbar" data-iconpos="top" >
		<ul>
			<li><a href="?action=main" data-icon="home"><?= _('Homescreen') ?></a></li>
			<li><a href="?action=ExtendedProfile" data-icon="profile" class="ui-btn-active"><?= _('Profile'); ?></a></li>
			<li><a href="#" data-icon="star" ><?= _('Social'); ?></a></li>	
		</ul>
	</div>
</div>

<? include("footer.php"); ?>