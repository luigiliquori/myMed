<div data-role="page" data-ajax="false">

	<? $title = _("Details");
	print_header_bar(true, false, $title); ?>
	
	<div data-role="content">
	
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		<div data-role="collapsible-set" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
				
				<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
					<form action="index.php?action=Blog" method="POST" data-ajax="false">
						<input type="hidden" name="authorID" value="<?= $this->result->publisherID ?>" />
						<input type="hidden" name="pred1" value="<?= $this->result->pred1 ?>" />
						<input type="hidden" name="pred2" value="<?= $this->result->pred2 ?>" />
						<input type="hidden" name="pred3" value="<?= $this->result->pred3 ?>" />
		 				<input type="hidden" name="method" value="Delete" />
						<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= _('Delete publication') ?>" />
		 			</form>
				<? } ?> 
			
				<h3><?= $this->result->pred2 ?></h3>
				
				<!-- Publication REPUTATION -->
	    		<div style="position: absolute; top:110px; right: 25px;">
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
					<a data-role="button" data-mini="true" data-icon="star" href="#popupReputationBlog1" data-rel="popup" data-inline="true" style="text-decoration:none;" ><?= _("Rate publication") ?></a>	
				</div>
				
				<div data-role="popup" id="popupReputationBlog1" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Do you like the project idea ?") ?><br /><br />
					<form action="#" method="get" data-ajax="false">
						<input type="hidden" name="action" value="updateReputationBlog" />
						<input type="hidden" name="isData" value="1" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						<label for="reputation"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
						<input type="range" name="reputation" id="reputation" value="3" min="1" max="5" data-mini="true" step="1"/>
						<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g"/>
					</form>
				</div>
				<!-- END Publication REPUTATION -->
								
				<p style="position: relative; margin-left: 30px;">
					<b><?= _("Cathegory")?></b>: <?= $this->result->pred1 ?><br/>
					<b><?= _("Date")?></b>: <?= date('Y-m-d', $this->result->begin) ?><br/>
				</p>
				
				<br/>
				
				<!-- Publication text (data) -->
				<?= $this->result->data1 ?><br/><br />
				
				<!-- Author REPUTATION - commented out -->
				<?php /* ?>
				<p>
					Publisher ID: <?= $this->result->publisherID ?><br/>
					Reputation: 
					<a href="#popupReputationAuthorBlog" data-rel="popup">
					
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							<?php if($i*20-20 < $this->reputation["author"] ) { ?>
								<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
							<?php } else { ?>
								<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							<?php } ?>
						<? } ?>
						</a>
				</p>
				
				<div data-role="popup" id="popupReputationAuthorBlog" class="ui-content" Style="text-align: center;">
					<?= _("Do you like the author?") ?><br /><br />
					<a href="?action=updateReputationBlog&reputation=10&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="g" data-icon="plus">Of course yes!</a><br />
					<a href="?action=updateReputationBlog&reputation=0&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="r" data-icon="minus">No, not really...</a>
				</div>
				<?php */ ?>
				<!-- END Author REPUTATION -->
				
				
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
		 			<form action="index.php?action=blog" method="POST" data-ajax="false">
		 				<textarea name="wrapped1"></textarea>
		 				<input type="hidden" name="method" value="Comment" />
						<input type="submit" value="<?= _('Comment') ?>" />
		 			</form>
	 	
	 				</div>
			</div>
		
		</div>
	
	</div>
	
</div>
