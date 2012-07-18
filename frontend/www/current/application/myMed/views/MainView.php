<? include("header.php"); ?>

<div id="home" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content" class="content">
	
		<div class="ui-grid-b" Style="padding: 10px;">
			<?php if ($handle = opendir(MYMED_ROOT . '/application')) {
				$hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp", "myMed_old");
				$column = "a";
			    while (false !== ($file = readdir($handle))) {
			    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, $hiddenApplication)) { ?>
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
			
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="#home" data-transition="none" data-back="true" data-icon="grid" class="ui-btn-active ui-state-persist">Applications</a></li>
					<li><a href="#profile" data-transition="none" data-icon="profile">Profil</a></li>
				</ul>
			</div>
		</div>
			
	</div>

</div>

<? include("ProfileView.php"); ?>
<? include("UpdateProfileView.php"); ?>

<? include("footer.php"); ?>