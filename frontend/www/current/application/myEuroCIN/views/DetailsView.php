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
			else $status = _("Validated publication"); ?>
			<div data-role="header" data-theme="e">
				<h1 style="white-space: normal;"> <?= $status ?> </h1>
			</div>
		<? } ?>
		
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			<h3><?= _("Description") ?></h3>
				<div>
				 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :?>
					<div style="position: absolute; right: 24px;">
						<?
						if(isset($_SESSION['myEuroCIN'])):	// UPDATE/DELETE
							 if($_GET['author']==$_SESSION['user']->id && !isset($this->result->old_publication)){ ?> <!-- the user is the author of this publication: can update -->
								<a data-role="button" data-icon="pencil" data-mini="true" data-inline="true" href="?action=publish&method=modify_publication&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>"><?= _("Edit")?></a>
						  <? }
						  // It is not possible modify publication made with the old myEuroCIN
						  	if(($this->result->publisherID==$_SESSION['user']->id || $_SESSION['myEuroCIN']->permission == '2') && !isset($this->result->old_publication) ){?>
  								<a type="button" data-mini="true" href="#popupDeleteAnnonce" data-theme="r" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Delete') ?></a>
  										
  								<!-- Pop up delete -->	
  								<div data-role="popup" id="popupDeleteAnnonce" class="ui-content" Style="text-align: center; width: 18em;">
  								 <? if($_SESSION['myEuroCIN']->permission == '2' && $this->result->publisherID!=$_SESSION['user']->id){ ?>
  								 		<p style="font-size:85%;"> <?= _("You can attach a message to inform the author (or just click on Delete):"); ?> </p>
  								 		<form action="?action=publish&method=delete" method="POST" data-ajax="false">
  								 			<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>
  											<input type="hidden" name="publisher" value="<?= $this->result->publisherID ?>" />
											<input type="hidden" name="type" value="<?= $this->result->type ?>" />
											<input type="hidden" name="begin" value="<?= $this->result->begin ?>" />
											<input type="hidden" name="expire_date" value="<?= $this->result->expire_date  ?>" />
											<input type="hidden" name="Nazione" value="<?= $this->result->Nazione ?>" />
											<input type="hidden" name="Lingua" value="<?= $this->result->Lingua ?>" />
											<input type="hidden" name="validated" value="<?= $this->result->validated ?>" />
											<input type="hidden" name="data" value="<?= $this->result->getTitle() ?>" />
											<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
											<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
											<? if(isset($this->result->Arte_Cultura)) 
												echo '<input type="hidden" name="Arte_Cultura" value="on" />' ?> 
											<? if( isset($this->result->Natura) ) 
												echo '<input type="hidden" name="Natura" value="on" />' ?> 
											<? if( isset($this->result->Tradizioni) ) 
												echo '<input type="hidden" name="Tradizioni" value="on" />' ?> 
											<? if( isset($this->result->Enogastronomia) ) 
												echo '<input type="hidden" name="Enogastronomia" value="on" />' ?> 
											<? if( isset($this->result->Benessere) ) 
												echo '<input type="hidden" name="Benessere" value="on" />;' ?> 
											<? if( isset($this->result->Storia) ) 
												echo '<input type="hidden" name="Storia" value="on" />' ?> 
											<? if( isset($this->result->Religione) ) 
												echo '<input type="hidden" name="Religione" value="on" />' ?> 
											<? if( isset($this->result->Escursioni_Sport) ) 
												echo '<input type="hidden" name="Escursioni_Sport" value="on" />' ?> 
					  						<input data-role="button" type="submit" data-theme="r" data-icon="ok" data-inline="true" value="<?= _('Delete') ?>" />
  										</form>
  										<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>
  						
  								 <? }else{
  										echo _("Are you sure you want to delete this publication?") ?>
  										<br />
  										<fieldset class="ui-grid-a">
  											<div class="ui-block-a">
  												<form action="?action=publish&method=delete" method="POST" data-ajax="false">
		  											<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
													<input type="hidden" name="type" value="<?= $this->result->type ?>" />
													<input type="hidden" name="begin" value="<?= $this->result->begin ?>" />
													<input type="hidden" name="expire_date" value="<?= $this->result->expire_date  ?>" />
													<input type="hidden" name="Nazione" value="<?= $this->result->Nazione ?>" />
													<input type="hidden" name="Lingua" value="<?= $this->result->Lingua ?>" />
													<input type="hidden" name="validated" value="<?= $this->result->validated ?>" />
													<input type="hidden" name="data" value="<?= $this->result->getTitle() ?>" />
													<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
													<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
													<? if(isset($this->result->Arte_Cultura)) 
														echo '<input type="hidden" name="Arte_Cultura" value="on" />' ?> 
													<? if( isset($this->result->Natura) ) 
														echo '<input type="hidden" name="Natura" value="on" />' ?> 
													<? if( isset($this->result->Tradizioni) ) 
														echo '<input type="hidden" name="Tradizioni" value="on" />' ?> 
													<? if( isset($this->result->Enogastronomia) ) 
														echo '<input type="hidden" name="Enogastronomia" value="on" />' ?> 
													<? if( isset($this->result->Benessere) ) 
														echo '<input type="hidden" name="Benessere" value="on" />' ?> 
													<? if( isset($this->result->Storia) ) 
														echo '<input type="hidden" name="Storia" value="on" />' ?> 
													<? if( isset($this->result->Religione) ) 
														echo '<input type="hidden" name="Religione" value="on" />' ?> 
													<? if( isset($this->result->Escursioni_Sport) ) 
														echo '<input type="hidden" name="Escursioni_Sport" value="on" />' ?> 
		  											<input data-role="button" type="submit" data-theme="g" data-icon="ok" data-inline="true" value="<?= _('Yes') ?>" />
  												</form>
  											</div>
  											<div class="ui-block-b">
  												<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
  											</div>
  										</fieldset>
  								 <? } ?>
  								</div>
  						  <? }
						endif;?>
					</div>
				 <? endif; ?>	
				 
					<!-- TITLE -->
					<h3><?= _("Title") ?>: <?= $this->result->getTitle(); ?></h3>
					<p> <? if( isset($this->result->expire_date) ) echo '<strong>'._("Deadline").': </strong>'.$this->result->expire_date; ?> </p>
					<p>
						<b><?= _("Locality") ?></b>: <?= Categories::$localities[$this->result->Nazione] ?><br/>
						<b><?= _("Language") ?></b>: <?= Categories::$languages[$this->result->Lingua] ?><br/>
						<b><?= _("Categories") ?></b>: 
						<? if( isset($this->result->Arte_Cultura) ) echo _("Art/Cultur "); ?> 
						<? if( isset($this->result->Natura) ) echo _("Nature "); ?>
						<? if( isset($this->result->Tradizioni) ) echo _("Traditions "); ?>
						<? if( isset($this->result->Enogastronomia) ) echo _("Enogastronimy "); ?>
						<? if( isset($this->result->Benessere) ) echo _("Wellness "); ?>
						<? if( isset($this->result->Storia) ) echo _("History "); ?>
						<? if( isset($this->result->Religione) ) echo _("Religion "); ?>
						<? if( isset($this->result->Escursioni_Sport) ) echo _("Sport "); ?>
					</p>
					
					<!-- TEXT -->
					<b><?= _("Description")?></b>: <?= $this->result->text ?>
				
					<!-- CONTACT -->			
					<p><b><?= _("Author")?></b> : 
					<?php
						// Other users' profiles are only accessible from myMed user with ExtendedProfile
						// This feature is not accessible for old publications
						$author = str_replace("MYMED_", "", $this->result->publisherID); 
						if(isset($_SESSION['myEuroCIN']) && !isset($this->result->old_publication)):
							echo "<a href=?action=extendedProfile&method=show_user_profile&user=".$this->result->publisherID.">".$author."</a>";
					 	else:
					 		echo $author."";
					 	endif;
					 	if($_GET['author']==$_SESSION['user']->id) echo " "._("(You)");
					?> 
					<br/></p>					
				</div>
				
				<?php // Reputation is not accessible for old publications 
					//if(!isset($this->result->old_publication)):?>
		    			
					<!-- Reputation -->
		    		<div>
		    			<!-- Publication reputation -->
		    			
		    			<p style="display:inline;"><?= _("Publication reputation")?>:</p>
							<?php
							// Disable reputation stars if there are no votes yet 
							if($this->reputation["value_noOfRatings"] == '0') : ?> 
								<?php for($i=1 ; $i <= 5 ; $i++) {?>
										<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
								<?php } ?>
								
						<?php else: ?>
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputation["value"] ) { ?>
									<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
								<?php } else { ?>
									<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
								<?php } ?>
							<? } ?>
					<?php endif; ?>
						<p style="display:inline; font-size:80%;"> <?php echo $this->reputation["value_noOfRatings"] ?> <?= _("rates")?> </p>
						
						<? /* can rate if logged in  */
						 if(isset($_SESSION['myEuroCIN']) && $this->result->publisherID != $_SESSION['user']->id ){
							 $date=strtotime(date('d-m-Y'));
							 $expired=false;
							 if(!empty($this->result->end)  && $this->result->end!="--"){
							 	$expDate=strtotime($this->result->end);
							 	if($expDate < $date){
							 		$expired=true;
							 	}
							 } 
			    			if (($expired==true || empty($this->result->end) || $this->result->end=="--") && $this->result->validated!="waiting") { ?>
								<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" style="text-decoration:none;" ><?= _("Rate publication") ?></a>	
						 <? } 
						 } ?>
						 <br/>
						<!-- Project reputation pop up -->
						<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
							<?= _("Do you like the project idea ?") ?><br /><br />
							<form id="form1" action="?action=updateReputation" method="get" data-ajax="false">
								<input type="hidden" name="action" value="updateReputation" />
								<input type="hidden" name="reputation" id="reputation" />
								<input type="hidden" name="isData" value="1" />
								<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
								<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
								<label for="reputationslider"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
								<input type="range" name="reputationslider" id="reputationslider" value="3" min="1" max="5" data-mini="true" step="1"/>
								<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation').val($('#reputationslider').val()*2);">
							</form>
						</div>	
						
						<!-- Author reputation (only for students and companies) -->	
						<p style="display:inline;"><?= _("Author reputation")?>:</p>
						<?php
							// Disable reputation stars if there are no votes yet 
							if($this->reputation["author_noOfRatings"] == '0') : ?> 
							<? for($i=1 ; $i <= 5 ; $i++) { ?>
									<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							<? } 		
						 	else: 
							 	for($i=1 ; $i <= 5 ; $i++) { ?>
								<? if($i*20-20 < $this->reputation["author"] ) { ?>
										<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
							 <?php } else { ?>
										<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							 <?php } ?>
							<? } ?>
						<? endif; ?>
						<p style="display:inline; font-size:80%;"> <?php echo $this->reputation["author_noOfRatings"] ?> <?= _("rates")?></p>							
						<?php 
							// A user cannot rate himself
							if (isset($_SESSION['myEuroCIN']) && !($this->result->publisherID == $_SESSION['user']->id) && $this->result->validated!="waiting") {
								echo '<a data-role="button" data-mini="true" data-inline="true" data-icon="star" href="#popupReputationAuthor" data-rel="popup" style="text-decoration:none;" > '. _("Rate author") .'</a>';
							}
						?>
	
						<!-- Author REPUTATION pop up -->
						<div data-role="popup" id="popupReputationAuthor" class="ui-content" Style="text-align: center; width: 18em;">
							<?= _("Do you like the author?") ?><br /><br />
							<form id="form2" action="?action=updateReputation" method="get" data-ajax="false">
								<input type="hidden" name="action" value="updateReputation" />
								<input type="hidden" name="reputation" id="reputation2"/>
								<input type="hidden" name="author" value="<?=  $_GET['author'] ?>" />
						 		<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
								<label for="reputationslider2"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 to 5") ?>:</p><br/></label>
								<input type="range" name="reputationslider2" id="reputationslider2" value="3" min="1" max="5" data-mini="true" step="1"/>
								<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation2').val($('#reputationslider2').val()*2);">
							</form>
						</div>	
						<!-- END Author REPUTATION -->
								
					</div> <!-- END Reputation -->
					
				<? //endif; ?>
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
						<a href="javascript:postToFeed('<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>','<?= $this->result->title ?>', '<?= str_replace('"',"&#39;", $this->result->text) ?>', '<?= str_replace("MYMED_", "", $this->result->publisherID) ?>', '<?= APPLICATION_NAME ?>')"><img src="img/facebookShare.png"/></a>	
					</div>
					
					<!-- Facebook_APP_ID defined in system/config.php -->
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
						        name: (title+" Author: "+author),
						        caption: appname,
						        description: desc
					    	});
						};
				    </script>
		    		<!-- END SHARE THIS -->
				<? endif; ?>
				<? endif; ?>
				<div style="height: 80px;"></div>

				<!-- Comments -->
				<div data-role="collapsible" data-content-theme="d">
	 				<? if(!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->is_guest)){?>
						<h3><?= _('Comments: <i>You have to be logged in to comment!</i>') ?></h3>
					<? }else{ ?>
	 					<h3><?= _('Comments') ?> </h3>
	 				<? } ?>
		 			<!-- displaying comments -->
					<br/>
		 			<?foreach ($this->result_comment as $item) :?>
		 				<div data-role="collapsible" data-theme="b" data-content-theme="b" data-mini="true" data-collapsed="false">
		 					
		 					<h3><?= $item->publisherName ?></h3>
		 					<div class="ui-grid-a">
		 						<!-- displaying photo of the user who added comment -->
		 						<div class="ui-block-a"><img src="<?= $item->wrapped2 ?>" align=left alt="Your photo here" width="100px" height="100px"/></div>
		 						<!-- displaying text -->
		 						<div class="ui-block-b"><?= $item->wrapped1 ?></div>
		 					</div>
		 					
		 				</div>
					<? endforeach ?>
		 			<? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest && $this->result->validated!="waiting"){ ?>
			 			<!-- adding new comments if logged -->
			 			<form action="?action=comment" method="POST" data-ajax="false">
			 				<textarea name="wrapped1"></textarea>
			 				<input type="hidden" name="method" value="Comment" />
							<input type="submit" value="<?= _('Comment') ?>" />
			 			</form>
	 				<? } ?>
	 			</div><br>
	 			
		</div>
		
	</div>
	
</div>
