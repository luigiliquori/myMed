<? require_once('notifications.php'); ?>

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
	
	<div data-role="content" >
	
		<?print_notification($this->success.$this->error);?>
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			<br />	
			<h3><?= _("Description") ?></h3>
				<div>
					<!-- APPLY -->
					<div style="position: absolute; right: 24px;">
						<?
						if(isset($_SESSION['myEdu'])):	
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
								<a data-role="button" data-inline="true" href="?action=publish&method=modify_publication&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>"><?= _("Edit")?></a>
						  <? }
						endif;?>
					</div>
						
					<!-- TITLE -->
					<h3><?= $this->result->title ?> :</h3>
					
					<!-- TEXT -->
					<?= $this->result->text ?>
				
					<!-- CONTACT -->			
					<p><b><?= _("Author")?></b> : 
					<?php
						// Other users' profiles are only accessible from myMed user with ExtendedProfile
						$author = str_replace("MYMED_", "", $this->result->publisherID); 
						if(isset($_SESSION['myEdu'])):
							echo "<a href=?action=extendedProfile&method=show_user_profile&user=".$this->result->publisherID.">".$author."</a>";
					 	else:
					 		echo $author."";
					 	endif;
					 	if($_GET['author']==$_SESSION['user']->id) echo " "._("(You)");
					?> 
					<br/></p>					
				</div>
				
				<!-- Reputation -->
				<?php  // Only user with myEdu Basic/Extended profile can rate 
				if(!$_SESSION['user']->is_guest) : ?>
	    		<div>
	    			<!-- Publication reputation -->
	    			<p style="display:inline; font-size:80%;">Publication rate:</p>
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
					<p style="display:inline; color: #2489CE; font-size:80%;"> <?php echo $this->reputation["value_noOfRatings"] ?> rates </p>
					
				 <? if ($this->result->publisherID != $_SESSION['user']->id) {
		    			$date=strtotime(date('d-m-Y'));
		    			$courseDate=strtotime($this->result->end);
		    			if(($this->result->category=='Course' && $courseDate<$date) || $this->result->category!='Course'){ ?>	
							<a data-role="button" data-inline="true" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" style="text-decoration:none;" ><?= _("Rate publication") ?></a>
					 <? }
				 	} ?>
					<br/>
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
					<?php if($this->publisher_profile->details['role']!='professor'): ?>
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
							if ($this->result->publisherID != $_SESSION['user']->id) {
								echo '<a data-role="button" data-mini="true" data-inline="true" data-icon="star" href="#popupReputationAuthor" data-rel="popup" style="text-decoration:none;" > '. _("Rate author") .'</a>';
							}
						?>

						<!-- Author REPUTATION pop up -->
						<div data-role="popup" id="popupReputationAuthor" class="ui-content" Style="text-align: center;">
							<?= _("Do you like the author?") ?><br /><br />
							<a href="?action=updateReputation&reputation=10&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="g" data-icon="plus"><?= _("Of course yes!")?></a><br />
							<a href="?action=updateReputation&reputation=0&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="r" data-icon="minus"><?= _("No, not really...")?></a>
						</div>	
						<!-- END Author REPUTATION -->
					<?php endif; ?>			
				
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
				

				<!-- Comments -->
				<div data-role="collapsible" data-content-theme="d">
				<? if($_SESSION['user']->is_guest){ ?>
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
		 			<? if(!$_SESSION['user']->is_guest){ ?>
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
					<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
						<? if($this->result->category=='Course'){ ?>
		 					<h3><?= _('Applicants') ?>: <?= ($this->result->currentappliers==-1)?0:$this->result->currentappliers ?>/<?= $this->result->maxappliers ?></h3>
			 			<? }else{ ?>
			 				<h3><?= _('Applicants') ?> </h3>
			 			<? } ?>
			 			<ul data-role="listview" data-filter="false" >
			 			<?foreach ($this->result_apply as $item) :?>
			 				<li>
				 				<div class="ui-grid-a">
									<div class="ui-block-a">
										<?= _("Name") ?>: <b><?= $item->publisherName ?></b>
									</div>
									<div class="ui-block-b">
										<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
										<div data-role="controlgroup" data-type="horizontal" style="float: right;">
											<? if($item->accepted!='accepted'): ?>
												<!-- <a style="float:left" type="button" href="#popupAccept" data-rel="popup" data-theme="g" data-inline="true" data-mini="true"><?= _('Accept') ?></a>-->
												<a style="float:left;" type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->pred1 ?>","<?= $item->pred2 ?>","<?= $item->pred3 ?>","<?= $item->author ?>","<?= $this->result->maxappliers ?>","<?= $this->result->currentappliers ?>","<?= $this->result->area ?>","<?= $this->result->category ?>","<?= $this->result->locality ?>","<?= $this->result->organization ?>","<?= $this->result->end ?>","<?= $this->result->text ?>","<?= $item->title ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Accept') ?></a>
											<? endif; ?>
											<!-- <a style="float:right" type="button" href="#popupRefuse" data-rel="popup" data-theme="r" data-inline="true" data-mini="true"><?= _('Refuse') ?></a>-->
											<a style="float:left;" type="button" href="#" onclick='generate_refuse_popup("<?= $item->publisher ?>","<?= $item->pred1 ?>","<?= $item->pred2 ?>","<?= $item->pred3 ?>","<?= $item->author ?>","<?= $this->result->maxappliers ?>","<?= $this->result->currentappliers ?>","<?= $this->result->area ?>","<?= $this->result->category ?>","<?= $this->result->locality ?>","<?= $this->result->organization ?>","<?= $this->result->end ?>","<?= $this->result->text ?>","<?= $item->title ?>","<?= $item->accepted ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Refuse') ?></a>
										</div>
										
										<script type="text/javascript">
											function generate_accept_popup(publisher, pred1,pred2,pred3,author,maxappliers,currentappliers,area,category,locality,organization,end,text,title){
												$("#popupAccept").html('<?= _("You can attach a message for the applier:") ?>\
													<form action="?action=apply&method=accept" method="POST" data-ajax="false">\
								 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
											 				<input type="hidden" name="method" value="<?= _('Accept')?>" />\
											 				<input type="hidden" name="publisher" value="'+publisher+'" />\
											 				<input type="hidden" name="pred1" value="'+pred1+'" />\
											 				<input type="hidden" name="pred2" value="'+pred2+' />\
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
											 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Send') ?>" />\
											 			</form>');
									 			$("#popupAccept").popup("open");
											}


											function generate_refuse_popup(publisher, pred1,pred2,pred3,author,maxappliers,currentappliers,area,category,locality,organization,end,text,title,accepted){
												$("#popupRefuse").html('<?= _("You can attach a message for the applier:") ?>\
														<form action="?action=apply&method=accept" method="POST" data-ajax="false">\
									 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
												 				<input type="hidden" name="method" value="<?= _('Accept')?>" />\
												 				<input type="hidden" name="publisher" value="'+publisher+'" />\
												 				<input type="hidden" name="pred1" value="'+pred1+'" />\
												 				<input type="hidden" name="pred2" value="'+pred2+' />\
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
												 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Send') ?>" />\
												 			</form>');
										 			$("#popupRefuse").popup("open");
											}
										</script>
										
										<!-- POPUP ACCEPT -->
										<div data-role="popup" id="popupAccept" class="ui-content" Style="text-align: center; width: 18em;">
											<!--<?= _("You can attach a message for the applier:") ?>
											
					 	    				<form action="?action=apply&method=accept" method="POST" data-ajax="false">
					 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>
								 				<input type="hidden" name="method" value="<?= _('Accept')?>" />
								 				<input type="hidden" name="publisher" value="<?= $item->publisher ?>" />
								 				<input type="hidden" name="pred1" value="<?= $item->pred1 ?>" />
								 				<input type="hidden" name="pred2" value="<?= $item->pred2 ?>" />
								 				<input type="hidden" name="pred3" value="<?= $item->pred3 ?>" />
								 				<input type="hidden" name="author" value="<?= $item->author ?>" />
								 				<input type="hidden" name="maxappliers" value="<?= $this->result->maxappliers ?>" />
								 				<input type="hidden" name="currentappliers" value="<?= $this->result->currentappliers ?>" />
								 				<input type="hidden" name="area" value="<?= $this->result->area ?>" />
								 				<input type="hidden" name="category" value="<?= $this->result->category ?>" />
								 				<input type="hidden" name="locality" value="<?= $this->result->locality ?>" />
								 				<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
								 				<input type="hidden" name="date" value="<?= $this->result->end ?>" />
								 				<input type="hidden" name="text" value="<?= $this->result->text ?>" />
								 				<input type="hidden" name="title" value="<?= $item->title ?>" />
								 				
												<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Send') ?>" />
								 			</form>-->
										</div>
										
										<!-- POPUP REFUSE -->
										<div data-role="popup" id="popupRefuse" class="ui-content" Style="text-align: center; width: 18em;">
											<!--<?= _("You can attach a message for the applier:") ?>
											<form action="?action=apply&method=refuse" method="POST" data-ajax="false">
												<textarea id="msgMail" name="msgMail" style="height: 120px;"></textarea><br>
								 				<input type="hidden" name="method" value="<?= _('Refuse')?>" />
								 				<input type="hidden" name="publisher" value="<?= $item->publisher ?>" />
								 				<input type="hidden" name="pred1" value="<?= $item->pred1 ?>" />
								 				<input type="hidden" name="pred2" value="<?= $item->pred2 ?>" />
								 				<input type="hidden" name="pred3" value="<?= $item->pred3 ?>" />
								 				<input type="hidden" name="title" value="<?= $item->title ?>" />
								 				<input type="hidden" name="author" value="<?= $item->author ?>" />
								 				<input type="hidden" name="maxappliers" value="<?= $this->result->maxappliers ?>" />
								 				<input type="hidden" name="currentappliers" value="<?= $this->result->currentappliers ?>" />
								 				<input type="hidden" name="area" value="<?= $this->result->area ?>" />
								 				<input type="hidden" name="category" value="<?= $this->result->category ?>" />
								 				<input type="hidden" name="locality" value="<?= $this->result->locality ?>" />
								 				<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
								 				<input type="hidden" name="date" value="<?= $this->result->end ?>" />
								 				<input type="hidden" name="text" value="<?= $this->result->text ?>" />
								 				<input type="hidden" name="accepted" value="<?= $item->accepted ?>" />
								 				
												<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Send') ?>" />-->
								 			</form>
										</div>
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
