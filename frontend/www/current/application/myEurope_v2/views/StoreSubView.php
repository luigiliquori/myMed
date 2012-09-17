<div id="storeSub" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content">
		
		<?php if(isset($_REQUEST["applicationStore"])) { ?>
		
			<div data-role="collapsible" data-mini="true" data-theme="a" data-content-theme="c" data-collapsed="false">
				<h3><?= $_REQUEST["applicationStore"] ?></h3>
				<img alt="<?= $_REQUEST["applicationStore"] ?>" src="../../application/<?= $_REQUEST["applicationStore"] ?>/img/icon.png" Style="position: absolute; width: 50px;">
				<div Style="position: absolute; left: 100px;">
					<p>Version: <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/version") ?></i><br />
					Mise à jour: <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/date") ?></i></p>
				</div>
				
				<div Style="position: absolute; right: 20px;">
					<select id="flip-<?= $_REQUEST["applicationStore"] ?>" name="flip-<?= $_REQUEST["applicationStore"] ?>"
						id="flip-<?= $_REQUEST["applicationStore"] ?>" data-role="slider" data-mini="true"
						onChange="SetApplicationStatus('<?= $_REQUEST["applicationStore"] ?>', $('#flip-<?= $_REQUEST["applicationStore"] ?>').val());">
						<option value="off"
						<?= $this->applicationStatus[$_REQUEST["applicationStore"]] == "off" ? "selected='selected'" : "" ?>>Off</option>
						<option value="on"
						<?= $this->applicationStatus[$_REQUEST["applicationStore"]] == "on"  ? "selected='selected'" : "" ?>>On</option>
					</select>
				</div>
				<div style="position: relative; height:60px;"></div>
			</div>
			
			<div data-role="collapsible" data-mini="true" data-theme="a" data-content-theme="c" data-collapsed="false">
				<h3>Screenshots</h3>
				
				<center>
					<img id="screenshot1" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot1.png" ?>" class="myScreenshot" Style="display: block;" />
					<img id="screenshot2" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot2.png" ?>" class="myScreenshot" />
					<img id="screenshot3" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot3.png" ?>" class="myScreenshot" />
					<br /><br />
					<div data-role="controlgroup" data-type="horizontal">
						<a href="#" onclick="prevScreenshot()" data-role="button" data-icon="arrow-l" data-iconpos="notext" data-inline="true"></a>
						<a href="#" onclick="nextScreenshot()" data-role="button" data-icon="arrow-r" data-iconpos="notext" data-inline="true"></a>
					</div>
				</center>
				
				<div Style="position: relative; left: 0px;">
			    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
			    		<?php if($i*20-20 < $this->reputation[$_REQUEST["applicationStore"] . STORE_PREFIX] ) { ?>
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
			
			<div data-role="collapsible" data-mini="true" data-theme="a" data-content-theme="c" data-collapsed="false">
				<h3 Style="color: lightblue;">Description</h3>
				<p><?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/description") ?></p>
				<div id="hidden-sharethis">
					<span class='st_facebook_large' displayText='Facebook'></span>
					<span class='st_twitter_large' displayText='Tweet'></span>
					<span class='st_linkedin_large' displayText='LinkedIn'></span>
					<span class='st_email_large' displayText='Email'></span>
				</div>
			</div>
			
		<?php } ?>
			
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user" >Profil</a></li>
				<li><a href="#store" data-transition="none" data-icon="star" class="ui-btn-active ui-state-persist">Store</a></li>
			</ul>
		</div>
	</div>

</div>
