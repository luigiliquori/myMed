<? include("header.php"); ?>

<? define('STORE_PREFIX' , '_store'); ?>

<div data-role="page">

	<? tab_bar_main("?action=store", 2); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content">
		
		<?php if(isset($_REQUEST["applicationStore"])) { ?>
		
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				<h3><?= $_REQUEST["applicationStore"] ?></h3>
				<img alt="<?= $_REQUEST["applicationStore"] ?>" src="../../application/<?= $_REQUEST["applicationStore"] ?>/img/icon.png" Style="position: absolute; width: 50px;">
				<div style="position: absolute; left: 200px;">
					<p>Version: <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/version") ?></i><br />
					Mise à jour: <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/date") ?></i></p>
				</div>
				<div Style="position: absolute; right: 20px;">
					<select data-role="slider" data-mini="true"
						onChange="toggleStatus('<?= $_GET['applicationStore'] ?>', $(this).val());">
						<option value="off"
						<?= $_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "off" ? "selected='selected'" : "" ?>>Off</option>
						<option value="on"
						<?= $_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "on"  ? "selected='selected'" : "" ?>>On</option>
					</select>
				</div>
				<div style="position: relative; height:60px;"></div>
			</div>
			
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				<h3>Screenshots</h3>
				
				<center>
					<img id="screenshot1" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot1.png" ?>" class="myScreenshot" Style="display: block;" />
					<img id="screenshot2" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot2.png" ?>" class="myScreenshot" />
					<img id="screenshot3" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot3.png" ?>" class="myScreenshot" />
					<br /><br />
					<div data-role="controlgroup" data-type="horizontal">
						<a href="#" onclick="prevScreenshot()" data-role="button" data-icon="arrow-l" data-iconpos="notext"></a>
						<a href="#" onclick="nextScreenshot()" data-role="button" data-icon="arrow-r" data-iconpos="notext"></a>
					</div>
				</center>
				
				<div Style="position: relative; left: 0px;">
			    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
			    		<?php if($i*20-20 < $_SESSION['reputation'][$_REQUEST["applicationStore"] . STORE_PREFIX] ) { ?>
			    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
			    		<?php } else { ?>
			    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
			    		<?php } ?>
			    	<? } ?>
		    	</div>
		    	<div data-role="controlgroup" data-type="horizontal">
					<a href="?action=store&applicationStore=<?= $_REQUEST["applicationStore"] ?>&reputation=1#storeSub" data-role="button" data-inline="true" rel="external">+1</a>
					<a href="?action=store&applicationStore=<?= $_REQUEST["applicationStore"] ?>&reputation=0#storeSub" data-role="button" data-inline="true" rel="external">-1</a>
				</div>
			</div>
			<a id="desc"></a>
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				<h3 Style="color: lightblue;">Description</h3>
				<p><?php @include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/description.php") ?></p>
				<div id="hidden-sharethis">
					<span class='st_facebook_large' displayText='Facebook'></span>
					<span class='st_twitter_large' displayText='Tweet'></span>
					<span class='st_linkedin_large' displayText='LinkedIn'></span>
					<span class='st_email_large' displayText='Email'></span>
				</div>
			</div>
			
		<?php } ?>
			
	</div>

</div>

<? include_once 'footer.php'; ?>
