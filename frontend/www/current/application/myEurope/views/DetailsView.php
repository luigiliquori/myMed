<div data-role="page">

	<? print_header_bar(true, false); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
				
				<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
					<form action="index.php?action=main" method="POST" data-ajax="false">
						<input type="hidden" name="theme" value="<?= $this->result->theme ?>" />
						<input type="hidden" name="other" value="<?= $this->result->other ?>" />
		 				<input type="hidden" name="method" value="Delete" />
						<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= translate('Delete publication') ?>" />
		 			</form>
				<? } ?> 
			
				<h3><?= $this->result->title ?></h3>
								
				<p style="position: relative; margin-left: 30px;">
					<b><?= _('keywords') ?></b>: <?= $this->result->theme ?>, <?= $this->result->other ?><br/>
					<b>End</b>: <?= $this->result->end ?><br/><br />
					
					<h3><?= $this->result->title ?> :</h3>
					<?= $this->result->text ?><br/><br />
					
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
					<?= _("Do you like the author?") ?><br /><br />
					<form action="#" method="get" data-ajax="false">
						<input type="hidden" name="action" value="updateReputation" />
						<input type="hidden" name="isData" value="1" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						<input type="range" name="reputation" id="reputation" value="5" min="0" max="10" data-mini="true"/>
						<input type="submit" value="Send" data-mini="true" data-theme="g"/>
					</form>
				</div>
				
				<br/>
				
				<p>
					<b>Contact</b>: <?= $this->result->publisherID ?><br/>
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
					<?= _("Do you like the content?") ?><br /><br />
					<a href="?action=updateReputation&reputation=10&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="g" data-icon="plus">Of course yes!</a><br />
					<a href="?action=updateReputation&reputation=0&predicate=<?= $_GET['predicate'] ?>&author=<?= $_GET['author'] ?>" data-mini="true" data-role="button" data-inline="true" rel="external" data-theme="r" data-icon="minus">No, not really...</a>
				</div>
				
			</div>
		
		</div>
	
	</div>
	
</div>

<? include_once 'MainView.php'; ?>
