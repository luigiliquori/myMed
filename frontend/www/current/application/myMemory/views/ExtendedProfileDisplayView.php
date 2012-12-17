<? include("header.php"); ?>
<? include("notifications.php")?>
<div data-role="page" id="ExtendedProfileView">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("NeedHelp"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>
	
	<div data-role="content" data-theme="a">
		
		<div class="description-box">
		<?= _("MyMemory_ViewProfileDesc"); ?>
		</div>
		
		<br />
		
		<div data-role="collapsible" data-collapsed="false" data-content-theme="d">
				<h3><?= _("MyInfos");?></h3>
				<h2><?=$_SESSION['user']->name?></h2>
				<p><?=$_SESSION['user']->login?></p>
				<p><?=$_SESSION['ExtendedProfile']->home?></p>
				<p><?= _("DiseaseLevel"); ?> : <strong><?
				/*This is ugly, but needed for the gettext.*/
				switch($_SESSION['ExtendedProfile']->diseaseLevel){
					case 1 :
						echo _('DiseaseLevel1');
						break;
					case 2 :
						echo _('DiseaseLevel2');
						break;
					case 3 :
						echo _('DiseaseLevel3');
						break;
				}?></strong></p>
		</div>	

		<div data-role="collapsible" data-collapsed="false"  data-content-theme="d">
				<h3><?= _("MyCaregiver"); ?></h3>
				<h2><?= $_SESSION['ExtendedProfile']->careGiver->nickname?></h2>
				<p><?= _("Phone") ?> : <a href="tel:<?= $_SESSION['ExtendedProfile']->careGiver->phone?>"><?= $_SESSION['ExtendedProfile']->careGiver->phone?></a></p>
				<p><?= _("Address") ?> : <?= $_SESSION['ExtendedProfile']->careGiver->address?></p>
				<p><?= _("Email") ?> : <a href="mailto:<?= $_SESSION['ExtendedProfile']->careGiver->email?>"><?= $_SESSION['ExtendedProfile']->careGiver->email?></a></p>
		</div>
		<div data-role="collapsible" data-content-theme="d">
				<h3><?= _("MyDoctor"); ?></h3>
				<h2><?= $_SESSION['ExtendedProfile']->doctor->nickname?></h2>
				<p><?= _("Phone") ?> : <a href="tel:<?= $_SESSION['ExtendedProfile']->doctor->phone?>"><?= $_SESSION['ExtendedProfile']->doctor->phone?></a></p>
				<p><?= _("Address") ?> : <?= $_SESSION['ExtendedProfile']->doctor->address?></p>
				<p><?= _("Email") ?> : <a href="mailto:<?= $_SESSION['ExtendedProfile']->doctor->email?>"><?= $_SESSION['ExtendedProfile']->doctor->email?></a></p>
		</div>
			
		<div data-role="collapsible"  data-content-theme="d">
			<h3><?= _("CallingList");?></h3>
			
			<?php foreach($_SESSION['ExtendedProfile']->callingList as $callingSlot=>$data) {?>
			
			<div data-role="collapsible" data-content-theme="d">
				<h3><?
				/* This is ugly but needed for the gettext*/
				switch($callingSlot){
					case 0:
						echo _('callingslot0');
						break;
					case 1:
						echo _('callingslot1');
						break;
					case 2:
						echo _('callingslot2');
						break;
					case 3:
						echo _('callingslot3');
						break;
				}
				?></h3>
				<h2><?= $data->nickname?></h2>
				<p><?= _("Phone") ?> : <a href="tel:<?= $data->phone?>"><?= $data->phone?></a></p>
				<?php
				if (!empty($data->address)) {
					echo "<p>" . _("Address") . " : " . $data->address . "</p>";
				}
				?>
				<p><?= _("Email") ?> : <a href="mail:<?= $data->email?>"><?= $data->email?></a></p>
			</div>
			<?php }?>
		</div>
	</div>

	<!-- Footer -->
	<div data-role="footer" data-id="myFooter" data-position="fixed">
		<a href="?action=ExtendedProfile&edit=false" data-role="button" data-rel="dialog" data-transition="pop" data-theme="b" data-icon="gear"><?= _("EditProfile"); ?></a>
	</div>
</div>	
<? include("footer.php"); ?>