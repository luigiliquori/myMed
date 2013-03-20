<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<?php

require_once("header.php");

?>

<div data-role="page">

	<?php tab_bar_main("?action=store", 5); ?>
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
			case "myEuroCIN":
				$googleLink ="com.app.myeurocin";
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
						<strong>Mise à jour:</strong> <i> <?php include (MYMED_ROOT . "/application/" . $_REQUEST["applicationStore"] . "/doc/date") ?></i><br/><br/><br/><br/><br/>
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
				<div style="position: relative; height:50px;"></div>
				
			 <? if(!$_SESSION['user']->is_guest) :?>
				<!-- SHARES -->
				<div style="position: absolute; right: 24px;">
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="my_Europe" data-url="<?= str_replace('@','%40','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>">Tweet</a>
    				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</div>
				<div style="position: absolute; right: 95px;">
					<g:plusone size="tall"></g:plusone>
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'en';}</script>
				</div>
				<div style="position: absolute; right: 150px; padding-top:40px;">
					<script src='http://connect.facebook.net/en_US/all.js'></script>
					<a href="javascript:postToFeed('<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>','<?= $_REQUEST["applicationStore"] ?>', '<?= APPLICATION_NAME ?>')"><img src="mymed/img/facebookShare.png"/></a>	
				</div>
				<div id="fb-root"></div>
			    <script>  
					window.fbAsyncInit = function() {
					    FB.init({appId: <?= Facebook_APP_ID?>, status: true, cookie: true, xfbml: true});
					  };
				    function postToFeed(url, title, appname) {
				    	FB.login(function(response) {
				            if (response.authResponse) {
				            	fbShare(url, title, appname);
				            }
				        }, {scope: 'publish_stream'});
				    }
				      
				    var fbShare = function(url, title, appname) {
					    alert(url);
				    	FB.ui({
					    	method: 'feed',
					        display: "iframe",
					        link: url,
					        picture: 'http://www.mymed.fr/application/myMed/img/logo-mymed-250c.png',
					        name: ("Presentation of "+title),
					        caption: appname
					        //description: desc
				    	});
					};
			    </script>
				<div style="height: 80px;"></div>
	    		<!-- END SHARES -->
	    	 <? endif;?>
			</div>
			
			<div data-role="collapsible" data-mini="true" data-theme="c" data-content-theme="d" data-collapsed="false">
				<h3>Screenshots</h3>
				
				<center>
					<img id="screenshot1" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot1.png" ?>" class="myScreenshot" Style="display: block;" />
					<img id="screenshot2" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot2.png" ?>" class="myScreenshot" />
					<!-- <img id="screenshot3" alt="<?= $_REQUEST["applicationStore"] ?>" src="<?= APP_ROOT . "/../" . $_REQUEST["applicationStore"] . "/doc/screenshot3.png" ?>" class="myScreenshot" />-->
					<br /><br />
					<div data-role="controlgroup" data-type="horizontal">
						<a href="#" onclick="prevScreenshot()" data-role="button" data-icon="arrow-l" data-iconpos="left"> </a>
						<a href="#" onclick="nextScreenshot()" data-role="button" data-icon="arrow-r" data-iconpos="right"> </a>
					</div>
				</center>
				

				<!-- APP REPUTATION -->
				<?php if(!$_SESSION['user']->is_guest) :?>
				<br/>	
    			<div Style="position: relative; left: 0px;">
	    			<p style="display:inline;" ><?= _("Application reputation")?>: </p>
			    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
			    		<?php if($i*20-20 < $_SESSION['reputation'][STORE_PREFIX . $_REQUEST["applicationStore"]] ) { ?>
			    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="12" Style="left: <?= $i ?>0px;" />
			    		<?php } else { ?>
			    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="12" Style="left: <?= $i ?>0px;"/>
			    		<?php } ?>
			    	<? } ?>
			    			
					<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupScoreApp" data-rel="popup" style="text-decoration:none;" ><?= _("Rate this app") ?></a>	
				</div>
				
				<!-- Application reputation pop up -->
				<div data-role="popup" id="popupScoreApp" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Do you like this application ?") ?><br /><br />
					<form id="form1" action="?action=store&applicationStore=<?= $_REQUEST["applicationStore"] ?>&reputation=1#storeSub" method="POST" data-ajax="false">
						
						<input type="hidden" name="reputation" id="reputation" />				
						<label for="reputationslider"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
						<input type="range" name="reputationslider" id="reputationslider" value="3" min="1" max="5" data-mini="true" step="1"/>
						<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation').val($('#reputationslider').val()*2);">
					</form>
				</div>
				
				
				
				
				
				
				<?php endif;?>	
				
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
