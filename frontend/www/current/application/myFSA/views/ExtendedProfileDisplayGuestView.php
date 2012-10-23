<? include("header.php"); ?>
<div data-role="page" id="PublishView" data-theme="b">
<div class="wrapper">

<div data-role="header" data-theme="b" data-position="fixed">
	
	<h1><?= APPLICATION_NAME ?></h1>
	
	<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="power" data-iconpos="notext">Deconnexion</a>
	
	<? include("notifications.php")?>
	
</div>

<div data-role="content" data-theme="b">
	
	<ul data-role="listview" class="ui-listview">
		<li class="ui-li ui-li-static ui-body-a ui-li-has-thumb">
			<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ui-li-thumb"/>
			<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
			<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
		</li>

		<?php if ($_SESSION["profileFilled"] == "guest") {?>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading"> Full profile information</h3>
			<div>
						You are logged as a guest.
			</div>
		</li>		
		<?php }?>
	</ul>	
</div>
<? include("footer.php"); ?>