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
								
	    		<!-- REPUTATION -->
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
					<a data-role="button" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" data-inline="true" style="text-decoration:none;" ><?= _("Rate project") ?></a>	
				</div>
				
				<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Do you like the project idea ?") ?><br /><br />
					<form id="form1" action="#" method="get" data-ajax="false">
						<input type="hidden" name="action" value="updateReputation" />
						<input type="hidden" name="isData" value="1" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						<label for="reputation"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
						<input type="range" name="reputation" id="reputation" value="3" min="1" max="5" data-mini="true" step="1"/>
						<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g"/>
					</form>
				</div>
				<!-- END REPUTATION -->
	    		
				<div>
					<!-- TITLE -->
					<h3><?= $this->result->title ?> :</h3>
					
					<!-- TEXT -->
					<?= $this->result->text ?>
				
					<!-- CONTACT -->			
					<p><b><?= _("Author")?></b> :<a href="<?= '?action=extendedProfile&user='.$this->result->publisherID ?>"><?= str_replace("MYMED_", "", $this->result->publisherID) ?> </a><br/></p>
					<!-- Keywords an timeline
					<p style="position: relative; margin-left: 30px;">
						<b><?= _('keywords') ?></b>: <?= $this->result->theme ?>, <?= $this->result->other ?><br/>
						<b>End</b>: <?= $this->result->end ?><br/><br />
					</p> -->
					
					<!-- DELETE 
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="index.php?action=main" method="POST" data-ajax="false">
							<input type="hidden" name="theme" value="<?= $this->result->theme ?>" />
							<input type="hidden" name="other" value="<?= $this->result->other ?>" />
			 				<input type="hidden" name="method" value="Delete" />
							<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= translate('Delete publication') ?>" />
			 			</form>
					<? } ?> 
					-->
				
				</div>
			</div>
		</div>
		
	</div>
	
</div>
