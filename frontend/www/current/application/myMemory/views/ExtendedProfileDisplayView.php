<? include("header.php"); ?>
<!--  Javascript that disables the submit button as long as the checkbox is not checked -->
<script type="text/javascript">
	$('#agreement').change(function() {
		if (this.checked)
			$('#submitButton').button('enable');
		else
			$('#submitButton').button('disable');
	});
</script>

<!-- Header -->
<div data-role="header" data-position="inline">
	<a href="#" data-rel="back" data-role="button"  data-icon="back">Back</a>
	<h1>Profile</h1>
</div>

<div data-role="content" data-theme="a">
	
	<ul data-role="listview" class="ui-listview">
		<li class="ui-li ui-li-static ui-body-a ui-li-has-thumb">
			<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ui-li-thumb"/>
			<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
			<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
			<p class="ui-li-desc">Level of the disease : <strong><?= $_SESSION['ExtendedProfile']->diseaseLevel?></strong></p>
		</li>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Caregiver : <?= $_SESSION['ExtendedProfile']->careGiver['name']?></h3>
				<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a">Phone :</div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $_SESSION['ExtendedProfile']->careGiver['phone']?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->careGiver['phone']?></a></div>
					<div class="mymem-profile-block-a">E-mail :</div>
					<div class="mymem-profile-block-b"><a href="mailto:<?= $_SESSION['ExtendedProfile']->careGiver['email']?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->careGiver['email']?></a></div>
				</div>
		</li>
		
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Doctor : <?= $_SESSION['ExtendedProfile']->doctor['name']?></h3>
			<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a">Phone :</div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $_SESSION['ExtendedProfile']->doctor['phone']?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->doctor['phone']?></a></div>
					<div class="mymem-profile-block-a">E-mail :</div>
					<div class="mymem-profile-block-b"><a href="mailto:<?= $_SESSION['ExtendedProfile']->doctor['email']?>" data-role="button" data-theme="b"><?= $_SESSION['ExtendedProfile']->doctor['email']?></a></div>
			</div>
		</li>
		
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Calling List</h3>
			
			<?php foreach($_SESSION['ExtendedProfile']->callingList as $callingSlot=>$data) {?>
			
			<div data-role="collapsible">
				<h3># <?= $callingSlot?></h3>
				<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a"><?= $data['name']?></div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $data['phone']?>" data-role="button" data-theme="b"><?= $data['phone']?></a></div>
					
				</div>
			</div>
			<?php }?>
			
			
			
			
		</li>
		
	</ul>
	
</div>
<? include("footer.php"); ?>