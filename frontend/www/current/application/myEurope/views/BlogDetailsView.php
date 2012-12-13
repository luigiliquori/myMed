<div data-role="page" data-ajax="false">

	<? print_header_bar(true, false); ?>
	
	<div data-role="content">
	
		<? include_once 'notifications.php'; ?>
	
		<div data-role="collapsible-set" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
				
				<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
					<form action="index.php?action=Blog" method="POST" data-ajax="false">
						<input type="hidden" name="authorID" value="<?= $this->result->publisherID ?>" />
						<input type="hidden" name="pred1" value="<?= $this->result->pred1 ?>" />
						<input type="hidden" name="pred2" value="<?= $this->result->pred2 ?>" />
						<input type="hidden" name="pred3" value="<?= $this->result->pred3 ?>" />
		 				<input type="hidden" name="method" value="Delete" />
						<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= translate('Delete publication') ?>" />
		 			</form>
				<? } ?> 
			
				<h3><?= $this->result->pred2 ?></h3>
								
				<p style="position: relative; margin-left: 30px;">
					<b>Cathegory</b>: <?= $this->result->pred1 ?><br/>
					<b>Date</b>: <?= date('Y-m-d', $this->result->begin) ?><br/>
					<b>Reputation</b>: 
					
					<a href="#popupReputation1" data-rel="popup">
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							<?php if($i*20-20 < $this->reputation["value"] ) { ?>
							<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
							<?php } else { ?>
							<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							<?php } ?>
						<? } ?>
					</a>
					
				</p>
				
				<div data-role="popup" id="popupReputation1" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Do you like the content?") ?><br /><br />
					<form action="#" method="get" data-ajax="false">
						<input type="hidden" name="action" value="updateReputationBlog" />
						<input type="hidden" name="isData" value="1" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						<input type="range" name="reputation" id="reputation" value="5" min="0" max="10" data-mini="true"/>
						<input type="submit" value="Send" data-mini="true" data-theme="g"/>
					</form>
				</div>
				
				<br/>
				<?= $this->result->data1 ?><br/><br />
				<p>
					Publisher ID: <?= $this->result->publisherID ?><br/>
					Reputation: 
					<a href="#popupReputation2" data-rel="popup">
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							<?php if($i*20-20 < $this->reputation["author"] ) { ?>
								<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
							<?php } else { ?>
								<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							<?php } ?>
						<? } ?>
						</a>
				</p>
				
				<div data-role="popup" id="popupReputation2" class="ui-content" Style="text-align: center;">
					<?= _("Do you like the author?") ?><br /><br />
					<a href="?action=updateReputationBlog&reputation=10&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="g" data-icon="plus">Of course yes!</a><br />
					<a href="?action=updateReputationBlog&reputation=0&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="r" data-icon="minus">No, not really...</a>
				</div>
				
				
				<div data-role="collapsible" data-content-theme="d">
	 				<h3><?= translate('Comments') ?></h3>
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
						<input type="submit" value="<?= translate('Comment') ?>" />
		 			</form>
	 	
	 				</div>
			</div>
		
		</div>
	
	</div>
	
</div>
