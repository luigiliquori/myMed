<? include("header.php"); ?>

<div id="home" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content" class="content">
		 
		<div data-role="collapsible" data-mini="true" data-content-theme="c" Style="position: relative; top: -20px;">
		    <h3>Plus d'applications</h3>
			<ul data-role="listview" data-filter="true"  data-mini="true">
				<?php if ($handle = opendir(MYMED_ROOT . '/application')) {
				    while (false !== ($file = readdir($handle))) {
				    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, $this->hiddenApplication)) { ?>
					    	<li>
					    		<img alt="<?= $file ?>" src="../../application/<?= $file ?>/img/icon.png" class="ui-li-icon" />
						    	<a href="#" class="myIcon">
						    	<div Style="position: absolute; right: 40px">
						    	<select id="flip-<?= $file ?>" name="flip-<?= $file ?>" id="flip-<?= $file ?>" data-role="slider" data-mini="true" onChange="SetCookie('<?= $file ?>Status', $('#flip-<?= $file ?>').val(), 365);  window.location.reload()">
									<option value="off" <?=(isset($_COOKIE[$file.'Status']) &&  $_COOKIE[$file.'Status'] == "off") || !isset($_COOKIE[$file.'Status']) ? "selected='selected'" : "" ?>>Off</option>
									<option value="on" <?= (isset($_COOKIE[$file.'Status']) &&  $_COOKIE[$file.'Status'] == "on") || in_array($file, $this->bootstrapApplication) ? "selected='selected'" : "" ?>>On</option>
								</select> 
								</div>
						    	<?= $file ?>
						    	</a>
					    	</li>
					    <?php } 
				    } 
				} ?>
			</ul>
		</div>
	
		<div class="ui-grid-b" Style="padding: 10px;">
			<?php if ($handle = opendir(MYMED_ROOT . '/application')) {
				$column = "a";
			    while (false !== ($file = readdir($handle))) {
			    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && 
			    	!in_array($file, $this->hiddenApplication) && 
			    	((isset($_COOKIE[$file.'Status']) && $_COOKIE[$file.'Status'] == "on") || (!isset($_COOKIE[$file.'Status'])) && in_array($file, $this->bootstrapApplication))) { ?>
				    	<div class="ui-block-<?= $column ?>">
					    	<a
					    	href="/application/<?= $file ?>"
					    	rel="external"
					    	class="myIcon"><img alt="<?= $file ?>" src="../../application/<?= $file ?>/img/icon.png" width="50px" ></a>
					    	<br>
					    	<span style="font-size: 9pt; font-weight: bold;"><?= $file ?></span>
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
			    } 
			} ?>
		</div>
		
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid" class="ui-btn-active ui-state-persist">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="profile">Profil</a></li>
				<li><a href="#" data-rel="dialog" data-icon="star" onClick="printDialog('hidden-sharethis', 'Partagez');">Partagez</a></li>
			</ul>
		</div>
	</div>

</div>

<? include("ProfileView.php"); ?>
<? include("UpdateProfileView.php"); ?>

<? include("footer.php"); ?>