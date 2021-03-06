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
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">

			<h3><?= _("Description") ?></h3>
				<div>
				 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :?>
					<!-- APPLY FOR STUDENTS IF PUBLICATION=COURSE -->
					<div style="position: absolute; right: 24px;"><br>
						<?
						if(isset($_SESSION['myTemplateExtended'])):	
							$date=strtotime(date('d-m-Y'));
							$expired=false;
							if(!empty($this->result->end)  && $this->result->end!="--"){
								$expDate=strtotime($this->result->end);
								if($expDate < $date){
									$expired=true;
								}
							}
							
							if($expired==false && $_GET['author']!=$_SESSION['user']->id){
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
						 				<input type="hidden" name="maxappliers" value="<?= $this->result->maxappliers ?>" />
						 				<input type="hidden" name="currentappliers" value="<?= $this->result->currentappliers ?>" />
						 				<input type="hidden" name="category" value="<?= $this->result->category ?>" />
						 				<input type="hidden" name="author" value="<?= $this->result->publisherID ?>" />
										<input type="submit" data-inline="true" data-theme="g" value="<?= _('Apply') ?>" />
						 			</form>
			 				 <? }
							 }
							 if($_GET['author']==$_SESSION['user']->id){ ?> <!-- the user is the author of this publication: can update -->
								<div data-type="horizontal">
									<a data-role="button" data-inline="true" href="?action=publish&method=modify_publication&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>"><?= _("Edit")?></a>
						  			<a data-role="button" data-inline="true" href="#popupDeleteAnnonce" data-theme="r" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Delete') ?></a>
						  			
						  			<!-- Pop up delete -->	
									<div data-role="popup" id="popupDeleteAnnonce" class="ui-content" Style="text-align: center; width: 18em;">
										<?= _("Are you sure you want to delete this publication?") ?><br /><br />
										<fieldset class="ui-grid-a">
											<div class="ui-block-a">
												<form action="?action=publish&method=delete" method="POST" data-ajax="false" >
													<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
													<input type="hidden" name="type" value="<?= $this->result->type ?>" />
													<input type="hidden" name="end" value="<?= $this->result->end  ?>" />
													<input type="hidden" name="area" value="<?= $this->result->area ?>" />
													<input type="hidden" name="category" value="<?= $this->result->category ?>" />
													<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
													<input type="hidden" name="title" value="<?= $this->result->title ?>" />
													<input type="hidden" name="text" id="text"/>
													<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
													<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
													<input type="submit" data-icon="ok" data-theme="g" data-inline="true" value="<?= _('Yes') ?>" />
									 			</form>
									 		</div>
									 		<div class="ui-block-b">
												<a href="#"  data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
											</div>
										</fieldset>
									</div>
								</div>
						  <? }
						endif;?>
					</div>
				<? endif; ?>
						
					<!-- TITLE -->
					<h3><?= $this->result->title ?> :</h3>
					
					<b><?= _('Deadline') ?></b>: <?= $this->result->end ?><br/>
					<b><?= _("Category") ?></b>: <?= Categories::$categories[$this->result->category] ?><br/>
					<b><?= _("Area") ?></b>: <?= Categories::$areas[$this->result->area] ?><br/>
					<b><?= _("Organization") ?></b>: <?= Categories::$organizations[$this->result->organization] ?><br/><br/>
							
					<!-- TEXT -->
					<b><?= _("Description") ?></b>: <?= $this->result->text ?>
				
					<!-- CONTACT -->			
					<p><b><?= _("Author")?></b> : 
					<?php
						// Other users' profiles are only accessible from myMed user with ExtendedProfile
						$author = str_replace("MYMED_", "", $this->result->publisherID); 
						if(isset($_SESSION['myTemplateExtended'])):
							echo "<a href=?action=extendedProfile&method=show_user_profile&user=".$this->result->publisherID.">".$author."</a>";
					 	else:
					 		echo $author."";
					 	endif;
					 	if($_GET['author']==$_SESSION['user']->id) echo " "._("(You)");
					?> 
					<br/></p>					
				</div>
				
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
					
				 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :
		    			// Courses can be rated only after the expiration date  
		    			$date=strtotime(date('d-m-Y'));
		    			$courseDate=strtotime($this->result->end);
		    			if(($this->result->category=='Course' && $courseDate<$date) || $this->result->category!='Course'){
		    			  	if($this->result->publisherID != $_SESSION['user']->id){ ?>
								<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" style="text-decoration:none;" ><?= _("Rate publication") ?></a>	<br/>
						<? } 
						}
					endif; ?>
					<br>
					<!-- Project reputation pop up -->
					<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
						<?= _("Do you like the project idea ?") ?><br /><br />
						<form id="form1" action="?action=updateReputation&reputa=10" method="get" data-ajax="false">
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
							if ($this->result->publisherID != $_SESSION['user']->id) {
								echo '<a data-role="button" data-mini="true" data-inline="true" data-icon="star" href="#popupReputationAuthor" data-rel="popup" style="text-decoration:none;" > '. _("Rate author") .'</a>';
							}
						endif;
						?>

						<!-- Author REPUTATION pop up -->
						<div data-role="popup" id="popupReputationAuthor" class="ui-content" Style="text-align: center; width: 18em;">
							<?= _("Do you like the author?") ?><br /><br />
							<form id="form2" action="?action=updateReputation" method="get" data-ajax="false">
								<input type="hidden" name="action" value="updateReputation" />
								<input type="hidden" name="reputation" id="reputation2"/>
								<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
								<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
								<label for="reputationslider2"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 to 5") ?>:</p><br/></label>
								<input type="range" name="reputationslider2" id="reputationslider2" value="3" min="1" max="5" data-mini="true" step="1"/>
								<input type="submit" value=<?= _("Rate")?> data-mini="true" data-theme="g" onclick="$('#reputation2').val($('#reputationslider2').val()*2);">
							</form>
						</div>	
					<!-- END Author REPUTATION -->
				</div> <!-- END Reputation -->
				
			 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) : ?>
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
				
				<?
				$text = strip_tags($this->result->text, '<div>');
				$text = str_replace('"',"", $text);
				$text = str_replace('\'',"", $text);
				$text = str_replace('’', "", $text);
				$text = htmlspecialchars($text);
				?>
					
				<!-- SHARE ON Facebook WITH DESC TITLE IMG -->
				<div style="position: absolute; right: 150px; padding-top:40px;">
					<script src='http://connect.facebook.net/en_US/all.js'></script>
					<a href="javascript:postToFeed('<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>','<?= $this->result->title ?>', '<?= $text ?>', '<?= str_replace("MYMED_", "", $this->result->publisherID) ?>', '<?= APPLICATION_NAME ?>')"><img src="img/facebookShare.png"/></a>	
				</div>
				
				<!-- Facebook_APP_ID defined in system/config.php -->
				<div id="fb-root"></div>
			    <script>  
					FB.init({appId: <?= Facebook_APP_ID?>, status: true, cookie: true, xfbml: true});

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
				<? endif; ?>
				
				<div style="height: 80px;"></div>
	    		<!-- END SHARE THIS -->

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
		 			<? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest){ ?>
			 			<!-- adding new comments if logged -->
			 			<form action="?action=comment" method="POST" data-ajax="false">
			 				<textarea name="wrapped1"></textarea>
			 				<input type="hidden" name="method" value="Comment" />
							<input type="submit" value="<?= _('Comment') ?>" />
			 			</form>
	 				<? } ?>
	 			</div><br>
	 			
	 			<? if($_GET['author']==$_SESSION['user']->id){ ?>
		 			<!-- STUDENTS APPLICATIONS -->
					<div data-role="collapsible" data-collapsed="true" data-theme="d">
						<? if($this->result->category=='Course'){ ?>
		 					<h3><?= _('Applicants') ?>: <?= ($this->result->currentappliers==-1)?0:$this->result->currentappliers ?>/<?= $this->result->maxappliers ?><b style="margin-left: 20px"><?= _("Waiting validation: ").$this->nbApplies_Waiting?></b></h3>
			 			<? }else{ ?>
			 				<h3><?= _('Applicants') ?>: <?= $this->nbApplies ?> <b style="margin-left: 20px"><?= _("Waiting validation: ").$this->nbApplies_Waiting?></b></h3>
			 			<? } ?>
			 			<ul data-role="listview" data-filter="false" >
			 			<?foreach ($this->result_apply as $item) :?>
			 				<li>
				 				<div class="ui-grid-a">
									<div class="ui-block-a">
										<?= _("Name") ?>: <b><a href="?action=extendedProfile&method=show_user_profile&user=<?= $item->publisher?>"><?= $item->publisherName ?></a></b>
									</div>
									<div class="ui-block-b">
										<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
										<div data-role="controlgroup" data-type="horizontal" style="float: right;">
											<? if($item->accepted!='accepted'): ?>
												<a style="float:left;" type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->pred1 ?>","<?= $item->pred2 ?>","<?= $item->pred3 ?>","<?= $item->author ?>","<?= $this->result->maxappliers ?>","<?= $this->result->currentappliers ?>","<?= $this->result->area ?>","<?= $this->result->category ?>","<?= $this->result->locality ?>","<?= $this->result->organization ?>","<?= $this->result->end ?>","<?= str_replace('"',"&#39;", $this->result->text) ?>","<?= $item->title ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Accept') ?></a>
											<? endif; ?>
											<a style="float:left;" type="button" href="#" onclick='generate_refuse_popup("<?= $item->publisher ?>","<?= $item->pred1 ?>","<?= $item->pred2 ?>","<?= $item->pred3 ?>","<?= $item->author ?>","<?= $this->result->maxappliers ?>","<?= $this->result->currentappliers ?>","<?= $this->result->area ?>","<?= $this->result->category ?>","<?= $this->result->locality ?>","<?= $this->result->organization ?>","<?= $this->result->end ?>","<?= str_replace('"',"&#39;", $this->result->text) ?>","<?= $item->title ?>","<?= $item->accepted ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Refuse') ?></a>
										</div>
										
										<script type="text/javascript">
											function generate_accept_popup(publisher, pred1,pred2,pred3,author,maxappliers,currentappliers,area,category,locality,organization,end,text,title){
												$("#popupAccept").html('<p style="font-size:85%;"><?= _("You can attach a message for the applier (or just click on Accept):") ?></p>\
													<form action="?action=apply&method=accept" method="POST" data-ajax="false">\
							 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
										 				<input type="hidden" name="publisher" value="'+publisher+'" />\
										 				<input type="hidden" name="pred1" value="'+pred1+'" />\
										 				<input type="hidden" name="pred2" value="'+pred2+'" />\
										 				<input type="hidden" name="pred3" value="'+pred3+'" />\
										 				<input type="hidden" name="author" value="'+author+'" />\
										 				<input type="hidden" name="maxappliers" value="'+maxappliers+'" />\
										 				<input type="hidden" name="currentappliers" value="'+currentappliers+'" />\
										 				<input type="hidden" name="area" value="'+area+'" />\
										 				<input type="hidden" name="category" value="'+category+'"/>\
										 				<input type="hidden" name="locality" value="'+locality+'" />\
										 				<input type="hidden" name="organization" value="'+organization+'" />\
										 				<input type="hidden" name="date" value="'+end+'" />\
										 				<input type="hidden" name="text" value="'+text+'" />\
										 				<input type="hidden" name="title" value="'+title+'" />\
										 				<input data-role="button" type="submit" data-icon="ok" data-theme="g" data-inline="true" value="<?= _('Accept') ?>" />\
										 			</form>\
										 			<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>\
											 		');
												$("#popupAccept").trigger("create");
									 			$("#popupAccept").popup("open");
											}


											function generate_refuse_popup(publisher, pred1,pred2,pred3,author,maxappliers,currentappliers,area,category,locality,organization,end,text,title,accepted){
												$("#popupRefuse").html('<p style="font-size:85%;"><?= _("You can attach a message for the applier (or just click on Refuse):") ?></p>\
													<form action="?action=apply&method=refuse" method="POST" data-ajax="false">\
							 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
										 				<input type="hidden" name="publisher" value="'+publisher+'" />\
										 				<input type="hidden" name="pred1" value="'+pred1+'" />\
										 				<input type="hidden" name="pred2" value="'+pred2+'" />\
										 				<input type="hidden" name="pred3" value="'+pred3+'" />\
										 				<input type="hidden" name="author" value="'+author+'" />\
										 				<input type="hidden" name="maxappliers" value="'+maxappliers+'" />\
										 				<input type="hidden" name="currentappliers" value="'+currentappliers+'" />\
										 				<input type="hidden" name="area" value="'+area+'" />\
										 				<input type="hidden" name="category" value="'+category+'"/>\
										 				<input type="hidden" name="locality" value="'+locality+'" />\
										 				<input type="hidden" name="organization" value="'+organization+'" />\
										 				<input type="hidden" name="date" value="'+end+'" />\
										 				<input type="hidden" name="text" value="'+text+'" />\
										 				<input type="hidden" name="title" value="'+title+'" />\
										 				<input type="hidden" name="accepted" value="'+accepted+'" />\
										 				<input data-role="button" type="submit" data-icon="ok" data-theme="r" data-inline="true" value="<?= _('Refuse') ?>" />\
										 			</form>\
										 			<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>\
												 	');
												$("#popupRefuse").trigger("create");
									 			$("#popupRefuse").popup("open");
											}
										</script>
										
										<!-- POPUP ACCEPT -->
										<div data-role="popup" id="popupAccept" class="ui-content" Style="text-align: center; width: 18em;"></div>
										
										<!-- POPUP REFUSE -->
										<div data-role="popup" id="popupRefuse" class="ui-content" Style="text-align: center; width: 18em;"></div>
									</div>
								</div>
			 				</li>
						<? endforeach ?>
						</ul>
					</div>
			 <? } ?>
		</div>
		
	</div>
	
</div>
