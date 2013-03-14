<?php

require_once("header.php");

?>

<div data-role="page">

	<?php tab_bar_main("?action=store", 2); ?>
	<?php include 'notifications.php'; ?>
	
	
	<div data-role="content">
		
		<?php if(isset($_REQUEST["applicationStore"])) { ?>
		
			<?php 
			$googleLink="No";
			$appleLink ="No";
		switch($_REQUEST["applicationStore"]){
			case "myEdu":
				$googleLink = "com.app.myEdu";
				$appleLink = "myedu/id599496750?mt=8&uo=4";
				break;
			case "myEurope":
				$googleLink = "com.app.myeurope";
				$appleLink = "myeurope/id594509288?mt=8&uo=4";
				break;
			case "myFSA":
				$googleLink = "com.app.myfsa";
				break;
			case "myMemory":
				$googleLink = "com.mymed.mymemory";
				break;
			case "myRiviera":
				$googleLink = "com.app.myriviera";
				break;
			default:
				$googleLink = "No";
				$appleLink = "No";
		}		
	?>
		
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				
				<h3><?= $_REQUEST["applicationStore"] ?></h3>
				
				<div >
					<img style="float:left; margin:10px;" alt="<?= $_REQUEST["applicationStore"] ?>" src="../../application/<?= $_REQUEST["applicationStore"] ?>/img/icon.png" Style="position: absolute; width: 50px;"/>
					<p style="margin:20px"> <br/>
						<strong>Version:</strong> <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/version") ?></i><br/>
						<strong>Mise à jour:</strong> <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/date") ?></i><br/><br/><br/>
						<i> <? include(MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/description.php") ?></i>
					</p>
				</div>
				
				<div style="position:absolute; right:30px; top: 140px;">
					<a data-role="button" onClick="
						<?php if($_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "off"): ?> 
							toggleStatus('<?= $_GET['applicationStore'] ?>', 'on')" data-theme="g"> Install </a>
						<?php else: ?>	
							toggleStatus('<?= $_GET['applicationStore'] ?>', 'off')" data-theme="r" > Uninstall </a>
						<?php endif; ?>
					
					
					<?php /* ?>
					<select data-role="slider" data-mini="true"
						onChange="toggleStatus('<?= $_GET['applicationStore'] ?>', $(this).val());">
						<option value="off"
						<?= $_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "off" ? "selected='selected'" : "" ?>>Off</option>
						<option value="on"
						<?= $_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "on"  ? "selected='selected'" : "" ?>>On</option>
					</select>
					*/ ?>
					<?php if($googleLink != "No"){?>
					<a href="https://play.google.com/store/apps/details?id=<?= $googleLink ?>">
  							<img alt="Get it on Google Play" src="https://developer.android.com/images/brand/en_generic_rgb_wo_45.png" /></a>
  					<?php }
  						if($appleLink !="No"){
  					?>
  					<a href="https://itunes.apple.com/fr/app/<?= $appleLink ?>" target="itunes_store"><img src="http://r.mzstatic.com/images/web/linkmaker/badge_appstore-lrg.gif" alt="myEurope - Luigi Liquori" style="border: 0;"/></a>
					<?php }?>
				</div>
				<div style="position: relative; height:60px;"></div>
			</div>
			
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				<h3>Screenshots</h3>
				
				<center>
					<img id="screenshot1" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot1.png" ?>" class="myScreenshot" Style="display: block;" />
					<img id="screenshot2" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot2.png" ?>" class="myScreenshot" />
					<!-- <img id="screenshot3" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot3.png" ?>" class="myScreenshot" />-->
					<br /><br />
					<div data-role="controlgroup" data-type="horizontal">
						<a href="#" onclick="prevScreenshot()" data-role="button" data-icon="arrow-l" data-iconpos="notext"></a>
						<a href="#" onclick="nextScreenshot()" data-role="button" data-icon="arrow-r" data-iconpos="notext"></a>
					</div>
				</center>
				

				<!-- APP REPUTATION -->
				<br/>	
    			<div Style="position: relative; left: 0px;">
    			<p style="display:inline; color: #2489CE;" >Application reputation: </p>
		    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
		    		<?php if($i*20-20 < $_SESSION['reputation'][STORE_PREFIX . $_REQUEST["applicationStore"]] ) { ?>
		    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="15" Style="left: <?= $i ?>0px;" />
		    		<?php } else { ?>
		    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="15" Style="left: <?= $i ?>0px;"/>
		    		<?php } ?>
		    	<? } ?>
		    	</div>		
				<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupScoreApp" data-rel="popup" style="text-decoration:none;" ><?= _("Rate this app") ?></a>	
				<br/>
					
				<!-- Appolication reputation pop up -->
				<div data-role="popup" id="popupScoreApp" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Do you like this application ?") ?><br /><br />
					<form id="form1" action="?action=store&applicationStore=<?= $_REQUEST["applicationStore"] ?>&reputation=1#storeSub" method="POST" data-ajax="false">
						
						<input type="hidden" name="reputation" id="reputation" />				
						<label for="reputationslider"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
						<input type="range" name="reputationslider" id="reputationslider" value="3" min="1" max="5" data-mini="true" step="1"/>
						<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation').val($('#reputationslider').val()*2);">
					</form>
				</div>	
				
			</div>
			<!-- <a id="desc"></a>
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				<h3 Style="color: lightblue;">Description</h3>
				<p><?php @include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/description.php") ?></p>
				<div id="hidden-sharethis">
					<span class='st_facebook_large' displayText='Facebook'></span>
					<span class='st_twitter_large' displayText='Tweet'></span>
					<span class='st_linkedin_large' displayText='LinkedIn'></span>
					<span class='st_email_large' displayText='Email'></span>
				</div>
			</div>-->
			
		<?php } ?>
			
	</div>

</div>

<? include_once 'footer.php'; ?>
