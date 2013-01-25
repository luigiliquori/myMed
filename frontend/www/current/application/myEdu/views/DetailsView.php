<? require_once('notifications.php'); ?>

<div data-role="page">
  <? $title = _("Details");
	 print_header_bar(true, false, $title); ?>
	
	<div data-role="content" >
	
		<?print_notification($this->success.$this->error);?>
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			
			<br />	
			<h3><?= _("Description") ?></h3>
								

				<div>
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
					?> 
					<br/></p>					
				</div>
				
				<!-- Reputation -->
				<?php  // Only user wit myEdu extended profile can rate 
					   if(isset($_SESSION['myEdu'])) : ?>
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
					<p style="display:inline; color: #2489CE; font-size:80%;"> <?php echo $this->reputation["value_noOfRatings"] ?> rates </p><br/>
					<a data-role="button" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" style="text-decoration:none;" ><?= _("Rate publication") ?></a>	
					
					<!-- Project reputation pop up -->
					<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
						<?= _("Do you like the project idea ?") ?><br /><br />
						<form id="form1" action="#" method="get" data-ajax="false">
							<input type="hidden" name="action" value="updateReputation" />
							<input type="hidden" name="isData" value="1" />
							<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
							<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
							<label for="reputation"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
							<input type="range" name="reputation" id="reputation" value="3" min="1" max="5" data-mini="true" step="1"/>
							<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g" onclick="$('#reputation').val($('#reputation').val()*2);">
						</form>
					</div>	
					
					<!-- Author reputation (only for students and companies) -->
					<?php if(!($this->publisher_profile->details['role']=='professor')): ?>
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
						<p style="display:inline; color: #2489CE; font-size:80%;"> <?php echo $this->reputation["author_noOfRatings"] ?> rates </p><br/>
						<a data-role="button" data-mini="true" data-icon="star" href="#popupReputationAuthor" data-rel="popup" style="text-decoration:none;" ><?= _("Rate author") ?></a>			
						
						<!-- Author REPUTATION pop up -->
						<div data-role="popup" id="popupReputationAuthor" class="ui-content" Style="text-align: center;">
							<?= _("Do you like the author?") ?><br /><br />
							<a href="?action=updateReputation&reputation=10&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="g" data-icon="plus">Of course yes!</a><br />
							<a href="?action=updateReputation&reputation=0&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="r" data-icon="minus">No, not really...</a>
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
	 				<h3><?= _('Comments') ?></h3>
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
		 			
		 			<!-- adding new comments -->
		 			<form action="?action=comment" method="POST" data-ajax="false">
		 				<textarea name="wrapped1"></textarea>
		 				<input type="hidden" name="method" value="Comment" />
						<input type="submit" value="<?= _('Comment') ?>" />
		 			</form>
	 	
	 			</div>
			</div>
		</div>
		
	</div>
	
</div>
