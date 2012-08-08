<div id="storeSub" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content">
		
		<?php if(isset($_REQUEST["applicationStore"])) { ?>
		
			<div Style="height:600px; width:100%; background-color: lightgray; position: absolute; top:0px; left:0px; opacity:0.5;"></div>
		
			<img alt="<?= $_REQUEST["applicationStore"] ?>" src="../../application/<?= $_REQUEST["applicationStore"] ?>/img/icon.png" Style="position: absolute; width: 50px;">
			<div Style="position: relative; top:15px; left: 60px;"><b><?= $_REQUEST["applicationStore"] ?></b></div>
			
			<div Style="position: absolute; right: 20px">
				<select id="flip-<?= $applicationName ?>" name="flip-<?= $applicationName ?>"
					id="flip-<?= $applicationName ?>" data-role="slider" data-mini="true"
					onChange="SetCookie('<?= $applicationName ?>Status', $('#flip-<?= $applicationName ?>').val(), 365);  window.location.reload()">
					<option value="off"
					<?= $this->applicationStatus[$applicationName] == "off" ? "selected='selected'" : "" ?>>Off</option>
					<option value="on"
					<?= $this->applicationStatus[$applicationName] == "on"  ? "selected='selected'" : "" ?>>On</option>
				</select>
			</div>
			
			<div style="position: relative; top:60px; text-align: center; height: 520px;">
				<center>
					<img id="screenshot1" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/screenshot1.png" ?>" class="myScreenshot" Style="display: block;" />
					<img id="screenshot2" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/screenshot2.png" ?>" class="myScreenshot" />
					<img id="screenshot3" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/screenshot3.png" ?>" class="myScreenshot" />
				</center>
				<br />
				<div data-role="controlgroup" data-type="horizontal">
					<a href="#" onclick="prevScreenshot()" data-role="button" data-icon="arrow-l" data-iconpos="notext" data-inline="true">left</a>
					<a href="#" onclick="nextScreenshot()" data-role="button" data-icon="arrow-r" data-iconpos="notext" data-inline="true">right</a>
				</div>
			</div>
			
			<h4 Style="color: lightblue;">Description</h4>
			<p><?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/description") ?></p>
			
			<p>Version: <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/version") ?></i></p>
			<p>MIs à jour: <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/date") ?></i></p>
		<?php } ?>
			
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="profile" >Profil</a></li>
				<li><a href="#store" data-transition="none" data-icon="star" class="ui-btn-active ui-state-persist">Store</a></li>
			</ul>
		</div>
	</div>

</div>
