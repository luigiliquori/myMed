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
  		// TODO bugfix: back to ResultsView does not work
  		// Save the last valid DetailView page referer 
  		// (another DetailView is not a valid referer)
  		
  		// If come from ResultsView goto ?action=find&search=true (FindView)
  		if(strpos($_SERVER['HTTP_REFERER'],"?action=find") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"&search=true")) {
  			$_SESSION['detailsview_valid_referer'] = '?action=find&search=true';
  		} 
  		// Do not save back link if come from DetailsView, updateReputation popup
  		// or ModifyPublicationView
  		else if(!strpos($_SERVER['HTTP_REFERER'],"?action=details") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"?action=updateReputation") &&
  		   !strpos($_SERVER['HTTP_REFERER'],"&method=modify_publication") 	) {
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
			<br />	
			<h3><?= _("Description") ?></h3>
				<div>
					<!-- APPLY FOR VOLUNTEERS -->
					<div style="position: absolute; right: 24px;">
						<?
						if(isset($_SESSION['myBenevolat']) && $_SESSION['myBenevolat']->details['type'] == 'volunteer'):
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
										break;
									}
								endforeach;
								if($applied==false){?>
						 			<form action="?action=apply&method=apply" method="POST" data-ajax="false">
						 				<input type="hidden" name="method" value="Apply" />
						 				<input type="hidden" name="title" value="<?= $this->result->title ?>" />
						 				<input type="hidden" name="mission" value="<?= $this->result->typeMission ?>" />
						 				<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr() ?>" />
						 				<input type="hidden" name="author" value="<?= $this->result->publisherID ?>" />
						 				<input type="hidden" name="id" value="<?= $this->result->id ?>" />
										<input type="submit" data-inline="true" data-theme="g" value="<?= _('Apply') ?>" />
						 			</form>
			 				 <? }
							}
						endif; ?>
						 	
						 	<!-- MODIFY / DELETE FOR THE OWNER (admin can delete too) -->
							<div data-type="horizontal">
							 <? if($this->result->publisherID==$_SESSION['user']->id): ?>
									<a style="float: left" data-role="button" data-icon="pencil" data-inline="true" href="?action=publish&method=modify_announcement&id=<?= $_GET['id'] ?>"><?= _("Edit")?></a>
							 <? endif; 
							    if($this->result->publisherID==$_SESSION['user']->id || $_SESSION['myBenevolat']->permission == '2'){?> 
									<a type="button" href="#popupDeleteAnnonce" data-theme="r" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Delete') ?></a>
									
									<!-- Pop up delete -->	
									<div data-role="popup" id="popupDeleteAnnonce" class="ui-content" Style="text-align: center; width: 18em;">
									 <? if($_SESSION['myBenevolat']->permission == '2' && $this->result->publisherID!=$_SESSION['user']->id){ // deleted by the admin: send a mail to the author to inform him
									 		echo _("You can attach a message for the author:"); ?> 
									 		<br />
									 		<form action="?action=publish&method=delete" method="POST" data-ajax="false">
									 			<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>
												<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
							 					<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr()?>" />
							 					<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
												<input type="hidden" name="title" value="<?=  $this->result->title?>" />
							 					<input data-role="button" type="submit" data-theme="r" data-icon="ok" data-inline="true" value="<?= _('Delete') ?>" />
							 				</form>
									 <? }else{
											echo _("Are you sure you want to delete this announcement?") ?>
											<br />
											<fieldset class="ui-grid-a">
												<div class="ui-block-a">
													<form action="?action=publish&method=delete" method="POST" data-ajax="false">
														<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
									 					<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr()?>" />
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
						
					<!-- TITLE -->
					<h3><?= $this->result->title ?> :</h3>
					
					<!-- TEXT -->
					<?= $this->result->text ?><br>
					
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
					  
					if(isset($_SESSION['myBenevolat']) && (($_SESSION['myBenevolat']->details['type'] == 'association') || ($_SESSION['myBenevolat']->details['type'] == 'admin'))){
						echo "<a href=?action=extendedProfile&method=show_user_profile&user=".$this->result->publisherID.">".$profile->details['associationname']."</a>";
						 		
						if($this->result->publisherID==$_SESSION['user']->id) echo " "._("(Yours)");
					}else{ // volunteer or guest
						echo $profile->details['associationname'];
					}?> 
					<br/></p>					
				</div>
				
				<!-- REPUTATION -->
				<?  // Only user with myBenevolat extended profile can rate 
				if(isset($_SESSION['myBenevolat'])) : ?>
	    			<div>
	    			<!-- Publication reputation -->
	    				<p style="display:inline; font-size:80%;">Publication rate:</p>
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
						<p style="display:inline; color: #2489CE; font-size:80%;"> <?php echo $this->reputation["value_noOfRatings"] ?> rates </p>
						 <? if ($this->result->publisherID != $_SESSION['user']->id) { ?>
								<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" style="text-decoration:none;" ><?= _("Rate publication") ?></a>	
						 <? } ?>
						<br/>
						<!-- Project reputation pop up -->
						<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
							<?= _("Do you like the project idea ?") ?><br /><br />
							<form id="form1" action="?action=updateReputation" method="POST" data-ajax="false">
								<input type="hidden" name="action" value="updateReputation" />
								<input type="hidden" name="reputation" id="reputation" />
								<input type="hidden" name="isData" value="1" />
								<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr()?>" />
							 	<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
							 	<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
							 					
								<label for="reputationslider"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
								<input type="range" name="reputationslider" id="reputationslider" value="3" min="1" max="5" data-mini="true" step="1"/>
								<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation').val($('#reputationslider').val()*2);">
							</form>
						</div>	
					
						<!-- Author reputation -->	
						<p style="display:inline; font-size:80%;">Author reputation:</p>
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
						<p style="display:inline; color: #2489CE; font-size:80%;"> <?php echo $this->reputation["author_noOfRatings"] ?> rates</p>							
						<?php 
							// A user cannot rate himself
							if (!($this->result->publisherID == $_SESSION['user']->id)) {
								echo '<a data-role="button" data-mini="true" data-inline="true" data-icon="star" href="#popupReputationAuthor" data-rel="popup" style="text-decoration:none;" > '. _("Rate author") .'</a>';
							}
						?>

						<!-- Author REPUTATION pop up -->
						<div data-role="popup" id="popupReputationAuthor" class="ui-content" style="text-align: center">
							<?= _("Do you like the author?") ?><br /><br />
							<form action="?action=updateReputation" method="POST" data-ajax="false">
								<input type="hidden" name="action" value="updateReputation" />
								<input type="hidden" name="reputation" value=10 />
								<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr()?>" />
							 	<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
							 	<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
							 	<input type="submit" value="<?= _("Of course yes!")?>" data-mini="true" data-theme="g" data-icon="plus">
							</form>
							<form action="?action=updateReputation" method="POST" data-ajax="false">
								<input type="hidden" name="action" value="updateReputation" />
								<input type="hidden" name="reputation" value=0 />
								<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr()?>" />
							 	<input type="hidden" name="author" value="<?=  $this->result->publisherID?>" />
							 	<input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
							 	<input type="submit" value="<?= _("No, not really...")?>" data-mini="true" data-inline="true" data-theme="r" data-icon="minus">
							</form>
						</div>	
						<!-- END Author REPUTATION -->
					</div> <!-- END Reputation -->
		  <?php endif; ?>

				
				<br/><br/>
				
				<!-- SHARE THIS -->
				<div style="position: absolute; right: 24px;">
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="my_Europe" data-url="<?= str_replace('@','%40','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>">Tweet</a>
	    			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</div>
						
				<div style="position: absolute; right: 95px;">
					<g:plusone size="tall"></g:plusone>
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'en';}</script>
				</div>
				
				<div style="position: absolute; right: 150px;">
					<a name="fb_share" type="box_count" share_url="<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" ></a>
   					<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
				</div>
				<div style="height: 80px;"></div>
	    		<!-- END SHARE THIS -->
			</div>
		</div>
	</div>
</div>
