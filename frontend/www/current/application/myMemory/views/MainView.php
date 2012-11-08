<? include("header.php"); ?>
<? include("notifications.php")?>
<!-- Header -->
<div data-role="header" data-position="inline">
<?php 
 if ($_SESSION['isMobile'])
 	echo '<a href="../../index.php?mobile_binary::logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete">Exit</a>';
 else 
 	echo '<a href="../../index.php" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete">Exit</a>';
?>
 	<h1></h1>
<!-- 	<a href="?action=ExtendedProfile" data-role="button" data-icon="gear">Profile</a> -->
<div data-role="button" class="ui-btn-right" data-icon="alert">Beta</div>
</div>

<div>
<a href="?action=GoingBack" rel="external" data-role="button" data-theme="a" class="mymed-big-button" data-icon="home">Je rentre chez moi !</a>
<a href="?action=NeedHelp" rel="external" data-role="button" data-theme="r" class="mymed-big-button" data-icon="alert">Au secours !</a>
</div>

<div data-role="footer" data-id="myFooter" data-position="fixed">
	<div data-role="navbar" data-iconpos="top" >
		<ul>
			<li><a href="?action=main" data-icon="home" class="ui-btn-active"><?= _('Homescreen') ?></a></li>
			<li><a href="?action=ExtendedProfile" data-icon="profile" ><?= _('Profile'); ?></a></li>
			<li><a href="?action=Social" data-icon="star" ><?= _('Social'); ?></a></li>
	<?php 
//	if ($_SESSION['isMobile'])
//		echo '<li><a href="../../index.php?mobile_binary::logout" rel="external" data-icon="delete">'._('Exit').'</a></li>';
//	else 
//		echo '<li><a href="../../index.php" rel="external" data-icon="delete">'._('Exit').'</a></li>';
	?>
			
		</ul>
	</div>
</div>
<? //include("footer.php"); ?>