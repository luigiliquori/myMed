<?php
/*
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
 */
?>
<!-- ------------------------ -->
<!-- Details View             -->
<!-- Show publication details -->
<!-- ------------------------ -->


<!-- Notification pop up -->
<? require_once('notifications.php'); ?>


<!-- Page View -->
<div data-role="page">
  
  
	<!-- Header bar -->
  	<? $title = _("Details");
  		
  		// If come from ResultsView goto ?action=find&search=true (FindView)
  		if(strpos($_SERVER['HTTP_REFERER'],"?action=find") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"&search=true")) {
  			$_SESSION['detailsview_valid_referer'] = '?action=find&search=true';
  		}else if(strpos($_SERVER['HTTP_REFERER'],"?action=publish&method=update")) {
  			$_SESSION['detailsview_valid_referer'] = '?action=publish&method=show_user_announcements';
  		} 
  		// Do not save back link if come from DetailsView, updateReputation popup, apply
  		// or ModifyPublicationView
  		else if(!strpos($_SERVER['HTTP_REFERER'],"?action=details") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"?action=updateReputation") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"?action=apply") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"&method=modify_announcement") 	) {
  			$_SESSION['detailsview_valid_referer'] = $_SERVER['HTTP_REFERER'];
  		}
  		print_header_bar($_SESSION['detailsview_valid_referer'], false, $title);

	?>
	
	<!-- Page content -->
	<div data-role="content" >
	
		<?print_notification($this->success.$this->error);?>
		
		<!-- Header: status -->
		<? if($this->result->publisherID==$_SESSION['user']->id){ 
			$status = "";
			if($this->result->validated=="waiting")
				$status = _("Waiting the administrator validation");
			else $status = _("Validated announcement"); ?>
			<div data-role="header" data-theme="e">
				<h1 style="white-space: normal;"> <?= $status ?> </h1>
			</div>
		<? } ?>
		
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">

			<h3><?= _("Description") ?></h3>
				<div>
				 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :?>
				 	<? if(isset($_SESSION['myBenevolat'])): ?>
						<div style="position: absolute; right: 24px;">
						
							<!-- APPLY FOR VOLUNTEERS --> 
						 <? if($_SESSION['myBenevolat']->details['type'] == 'volunteer'):
								$date=strtotime(date(DATE_FORMAT));
								$expired=false;
								/* can apply if date not expired */
								if(!empty($this->result->end)  && $this->result->end!="--"){
									$expDate=strtotime($this->result->end);
									if($expDate < $date){
										$expired=true;
									}
								}
								
								if($expired==false){
									$applied=false;
									foreach ($this->result_apply as $item) :
										if($item->publisherID==$_SESSION['user']->id){ // already applied
										$applied=true;
										if($item->accepted=='waiting')
											echo _("<b>Waiting application validation</b>");
										else
											echo _("<b>Member to this offer</b>");
										break;
									}
									endforeach;
									if($applied==false){?>
							 			<form action="?action=apply&method=apply" method="POST" data-ajax="false">
							 				<input type="hidden" name="method" value="Apply" />
							 				<input type="hidden" name="title" value="<?= $this->result->title ?>" />
							 				<input type="hidden" name="mission" value="<?= $this->result->typeMission ?>" />
							 				<input type="hidden" name="author" value="<?= $this->result->publisherID ?>" />
							 				<input type="hidden" name="id" value="<?= $this->result->id ?>" />
											<input type="submit" data-mini="true" data-inline="true" data-theme="g" value="<?= _('Apply') ?>" />
							 			</form>
				 				 <? }
								}
							endif; ?>
							 	
							 <!-- MODIFY / DELETE FOR THE OWNER (admin can delete too) -->
							<div data-type="horizontal">
						 	 <? if($this->result->publisherID==$_SESSION['user']->id): ?>
									<a style="float: left" data-role="button" data-icon="pencil" data-mini="true" data-inline="true" href="?action=publish&method=modify_announcement&id=<?= $_GET['id'] ?>"><?= _("Edit")?></a>
							 <? endif; 
								if($this->result->publisherID==$_SESSION['user']->id || $_SESSION['myBenevolat']->permission == '2'){?> 
									<a type="button" href="#popupDeleteAnnonce" data-theme="r" data-mini="true" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Delete') ?></a>
										
									<!-- Pop up delete -->	
									<div data-role="popup" id="popupDeleteAnnonce" class="ui-content" Style="text-align: center; width: 18em;">
									 <? if($_SESSION['myBenevolat']->permission == '2' && $this->result->publisherID!=$_SESSION['user']->id){ ?>
									 		<p style="font-size:85%;"> <?= _("You can attach a message to inform the association (or just click on Delete):"); ?> </p>
									 		<form action="?action=publish&method=delete" method="POST" data-ajax="false">
									 			<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>
												<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
												<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
												<input type="hidden" name="title" value="<?=  $this->result->title?>" />
												<input data-role="button" type="submit" data-theme="r" data-icon="ok" data-inline="true" value="<?= _('Delete') ?>" />
											</form>
											<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>
						
									 <? }else{
											echo _("Are you sure you want to delete this announcement?") ?>
											<br />
											<fieldset class="ui-grid-a">
												<div class="ui-block-a">
													<form action="?action=publish&method=delete" method="POST" data-ajax="false">
														<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
														<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
														<input data-role="button" type="submit" data-theme="g" data-icon="ok" data-inline="true" value="<?= _('Yes') ?>" />
													</form>
												</div>
												<div class="ui-block-b">
													<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
												</div>
											</fieldset>
									 <? } ?>
									</div>
							  <? } ?>
							</div>
						</div>
				 	<? endif; ?>
				 <? endif; ?>
					
					<!-- TITLE -->
					<h3><?= $this->result->title ?> :</h3>
					
					<b><?= _('Publication date') ?></b>: <?= $this->result->begin ?><br/>
					<b><?= _('Deadline') ?></b>: <?= $this->result->end ?>
				 <? if(!empty($this->result->end) && $this->result->end!="--"){
				 		$date = strtotime(date('d-m-Y'));
						$expiration_date = strtotime($this->result->end);
						if($date > $expiration_date){
							echo _("<b style='color:red;margin-left:10px'>ANNOUNCEMENT EXPIRED</b>");
						}
				 	} ?>
					<br/><br/>
					<b><?= _("Mission type") ?></b>: <?= Categories::$missions[$this->result->typeMission] ?><br/>
					<b><?= _("District") ?></b>: <?= Categories::$mobilite[$this->result->quartier] ?><br/>
					<b><?= _("Skills") ?></b>: 
				 <? if(gettype($this->result->competences)=="string"){ ?> <!-- only 1 skill -> string and not array -->
						<?= Categories::$competences[$this->result->competences]?><br/><br/>
				 <? }else{ ?>
						<? foreach($this->result->competences as $competences): echo Categories::$competences[$competences]." , "; endforeach;?><br/><br/>
				 <? } ?> 
					
					<!-- TEXT -->
					<b><?= _("Description")?></b>: <?= $this->result->text ?><br>
					
				 <? if($this->result->publisherID==$_SESSION['user']->id){
				 		$this->search_validated_apply();
						echo _("<p><b>Number of volunteers: </b>").count($this->validated_apply)."</p>";
					} ?>
					
					<!-- CONTACT: volunteers can only see the association name, no contact by email/phone... -->			
					<p><b><?= _("Association name")?></b> : 
					<?
					$user = new User($this->result->publisherID);
					try {
						$mapper = new DataMapper;
						$details = $mapper->findById($user);
					} catch (Exception $e) {}
					$profile = new myBenevolatProfile($details['profile']);
					try {
						$profile->details = $mapper->findById($profile);
					} catch (Exception $e) {}
					
					if(isset($_SESSION['myBenevolat']) && ((($_SESSION['myBenevolat']->details['type'] == 'association') && $_SESSION['myBenevolat']->permission == '1') || ($_SESSION['myBenevolat']->permission == '2'))){
						echo "<a href=?action=extendedProfile&method=show_user_profile&user=".$this->result->publisherID.">".$profile->details['associationname']."</a>";	
						if($this->result->publisherID==$_SESSION['user']->id) echo " "._("(Yours)");
					
					}else{ // volunteer, guest or association not validated
						echo $profile->details['associationname'];
					}?> 
					<br/></p>					
				</div>
				
				<!-- REPUTATION -->	
    			<br/>
    			<div>
    			<!-- Publication reputation -->
    				<p style="display:inline"><?= _("Announcement reputation")?>:</p>
					<? // Disable reputation stars if there are no votes yet 
					if($this->reputation["value_noOfRatings"] == '0') :  
				    	for($i=1 ; $i <= 5 ; $i++) {?>
							<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
				 	<?  } 			
				    else: 
						for($i=1 ; $i <= 5 ; $i++) { ?>
						<?   if($i*20-20 < $this->reputation["value"] ) { ?>
								<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
					   <?php } else { ?>
								<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
					   <?php } 
					 	} 
					endif; ?>
					<p style="display:inline; font-size:80%;"> <?php echo $this->reputation["value_noOfRatings"] ?> <?= _("rates")?> </p>
					 
				 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :?>
					 <? /* can rate if logged in and validated if association */
						if(isset($_SESSION['myBenevolat']) && (($_SESSION['myBenevolat']->details['type'] == 'association' && $_SESSION['myBenevolat']->permission == '1') || $_SESSION['myBenevolat']->permission == '2' || $_SESSION['myBenevolat']->details['type'] == 'volunteer')){
							 $date=strtotime(date(DATE_FORMAT));
							 $expired=false;
							 if(!empty($this->result->end)  && $this->result->end!="--"){
							 	$expDate=strtotime($this->result->end);
							 	if($expDate < $date){
							 		$expired=true;
							 	}
							 } 
						 	/* can rate the announcement if the user is not the author and if the date has expired  */
							if ($this->result->publisherID != $_SESSION['user']->id && ($expired==true || empty($this->result->end) || $this->result->end=="--") && $this->result->validated!="waiting") { ?>
								<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" style="text-decoration:none;" ><?= _("Rate announcement") ?></a>	
						 <? } 
						 }
					endif; ?>
					<br/>
					<!-- Project reputation pop up -->
					<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
						<?= _("Do you like the project idea ?") ?><br /><br />
						<form id="form1" action="?action=updateReputation" method="POST" data-ajax="false">
							<input type="hidden" name="action" value="updateReputation" />
							<input type="hidden" name="reputation" id="reputation" />
							<input type="hidden" name="isData" value="1" />
						 	<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
						 	<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
						 					
							<label for="reputationslider"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
							<input type="range" name="reputationslider" id="reputationslider" value="3" min="1" max="5" data-mini="true" step="1"/>
							<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation').val($('#reputationslider').val()*2);">
						</form>
					</div>	
				
					<!-- Author reputation -->	
					<p style="display:inline;"><?= _("Association reputation")?>:</p>
					<?php
						// Disable reputation stars if there are no votes yet 
						if($this->reputation["author_noOfRatings"] == '0') : ?> 
						<?php for($i=1 ; $i <= 5 ; $i++) {?>
								<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
					<?php } ?>		
					<?php else: ?>
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							<?php if($i*20-20 < $this->reputation["author"] ) { ?>
								<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
							<?php } else { ?>
								<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							<?php } ?>
						<? } ?>
					<?php endif; ?>
					<p style="display:inline; font-size:80%;"> <?php echo $this->reputation["author_noOfRatings"] ?> <?= _("rates")?></p>							
				 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :
						/* can rate if logged in and validated if association */
					 	if(isset($_SESSION['myBenevolat']) && $this->result->validated!="waiting" && (($_SESSION['myBenevolat']->details['type'] == 'association' && $_SESSION['myBenevolat']->permission == '1') || $_SESSION['myBenevolat']->permission == '2' || $_SESSION['myBenevolat']->details['type'] == 'volunteer')){
							if (!($this->result->publisherID == $_SESSION['user']->id)) {
								echo '<a data-role="button" data-mini="true" data-inline="true" data-icon="star" href="#popupReputationAuthor" data-rel="popup" style="text-decoration:none;" > '. _("Rate association") .'</a>';
							}
						}
					endif; ?>

					<!-- Author REPUTATION pop up -->
					<div data-role="popup" id="popupReputationAuthor" class="ui-content" Style="text-align: center; width: 18em;">
						<?= _("Do you like the author?") ?><br /><br />
						<form id="form2" action="?action=updateReputation" method="POST" data-ajax="false">
							<input type="hidden" name="action" value="updateReputation" />
							<input type="hidden" name="reputation" id="reputation2"/>
							<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
					 		<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
							<label for="reputationslider2"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 to 5") ?>:</p><br/></label>
							<input type="range" name="reputationslider2" id="reputationslider2" value="3" min="1" max="5" data-mini="true" step="1"/>
							<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation2').val($('#reputationslider2').val()*2);">
						</form>
					</div>	
					<!-- END Author REPUTATION -->
				</div> <!-- END Reputation -->

			 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :?>
				<br/><br/>
				<? if($this->result->validated!="waiting"): ?>
					<!-- SHARE THIS -->
					<div style="position: absolute; right: 24px;">
						<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="my_Europe" data-url="<?= str_replace('@','%40','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>">Tweet</a>
		    			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
							
					<div style="position: absolute; right: 95px;">
						<g:plusone size="tall"></g:plusone>
						<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'en';}</script>
					</div>
					
					<!-- SHARE ON Facebook WITH DESC TITLE IMG -->
					<div style="position: absolute; right: 150px; padding-top:40px;">
						<script src='http://connect.facebook.net/en_US/all.js'></script>
						<a href="javascript:postToFeed('<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>','<?= $this->result->title ?>', '<?= str_replace('"',"&#39;", $this->result->text) ?>', '<?= $profile->details['associationname'] ?>', '<?= APPLICATION_NAME ?>')"><img src="img/facebookShare.png"/></a>	
					</div>
					
					<!-- Facebook_APP_ID defined in system/config.php -->
					<div id="fb-root"></div>
				    <script>  
						window.fbAsyncInit = function() {
						    FB.init({appId: <?= Facebook_APP_ID?>, status: true, cookie: true, xfbml: true});
						  };
					    function postToFeed(url, title, desc, author, appname) {
					    	FB.login(function(response) {
					            if (response.authResponse) {
					            	fbShare(url, title, desc, author, appname);
					            }
					        }, {scope: 'publish_stream'});
					    }
					      
					    var fbShare = function(url, title, desc, author, appname) {
					    	FB.ui({
						    	method: 'feed',
						        display: "iframe",
						        link: url,
						        picture: 'http://www.mymed.fr/application/myMed/img/logo-mymed-250c.png',
						        name: (title+" Association: "+author),
						        caption: appname,
						        description: desc
					    	});
						};
				    </script>
		    		<!-- END SHARE THIS -->
		    	<? endif; ?>
		    <? endif; ?>
		    <div style="height: 80px;"></div>
			</div>
		</div>
	</div>
</div>
