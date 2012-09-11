<? include("header.php"); ?>

<? if(empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>


<div id="home" data-role="page">

	<? include_once ("header-bar-light.php"); ?>
	<? tabs_main("home", array(
			"home" => array(_('Applications'), "grid"),
			"profile" => array(_('Profile'), "user"),
			"store" => array(_('Store'), "star"),
	), true) ?>
	<? include("notifications.php")?>
	<div data-role="content">
		<br />
		<a href="<?= MYMED_URL_ROOT ?>/application/myEurope" rel="external">myEurope </a>(for Sarah or others)
		<br /><br /><br />
		full profile
		<pre style="text-align: left;"><? print_r($_SESSION['user']); ?></pre><br />
		profile fetched from openid/oauth
		<pre style="text-align: left;"><? print_r($_SESSION['user2']); ?></pre><br />

		<div class="ui-grid-b" Style="padding: 10px;">
			<?php $column = "a"; ?>
			<?php foreach ($this->applicationList as $applicationName) { ?>
				<?php if ($this->applicationStatus[$applicationName] == "on") { ?>
					<div class="ui-block-<?= $column ?>">
						<a href="/application/<?= $applicationName ?>" rel="external" class="myIcon"><img
							alt="<?= $applicationName ?>"
							src="../../application/<?= $applicationName ?>/img/icon.png" width="50px"></a>
						<br> <span style="font-size: 9pt; font-weight: bold;"><?= $applicationName ?> </span>
					</div>
					<?php 
			    	if($column == "a") {
			    		$column = "b";
			    	} else if($column == "b") {
			    		$column = "c";
			    	} else if($column == "c") {
			    		$column = "a";
			    		echo '</div><br /><div class="ui-grid-b">';
			    	}
				}   	 
			} ?>
		</div>
		
	</div>



</div>

<? include("ProfileView.php"); ?>
<? include("UpdateProfileView.php"); ?>
<? include("StoreView.php"); ?>

<? include("footer.php"); ?>