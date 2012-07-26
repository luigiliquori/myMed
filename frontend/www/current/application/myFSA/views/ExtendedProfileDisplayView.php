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
<div data-role="header" data-position="inline" data-theme="a">
	<a href="#" data-rel="back" data-role="button"  data-icon="back">Back</a>
	<h1>Profile</h1>
</div>

<div data-role="content" data-theme="a">
	
	<ul data-role="listview" class="ui-listview">
		<li class="ui-li ui-li-static ui-body-a ui-li-has-thumb">
			<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ui-li-thumb"/>
			<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
			<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
		</li>
		<!-- here copied text from notepad -->
		
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading">Full Profile : <?= $_SESSION['ExtendedProfile']->doctor['name']?></h3>
			<div class="mymem-profile-grid">
					<div class="mymem-profile-block-a">company address :</div>
					<div class="mymem-profile-block-b"><a href="# <?= $_SESSION['ExtendedProfile']->companyName?>" data-role="button" ><?= $_SESSION['ExtendedProfile']->companyName?></a></div>
					<div class="mymem-profile-block-a">Phone :</div>
					<div class="mymem-profile-block-b"><a href="tel:<?= $_SESSION['ExtendedProfile']->doctor['phone']?>" data-role="button" ><?= $_SESSION['ExtendedProfile']->doctor['phone']?></a></div>
					<div class="mymem-profile-block-a">Surname :</div>
					<div class="mymem-profile-block-b"><a href="mailto:<?= $_SESSION['ExtendedProfile']->doctor['email']?>" data-role="button"><?= $_SESSION['ExtendedProfile']->doctor['email']?></a></div>
			</div>
		</li>
		
	</ul>
	
</div>
<? include("footer.php"); ?>