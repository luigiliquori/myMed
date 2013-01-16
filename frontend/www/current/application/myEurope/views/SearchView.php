<div data-role="page" id="search">

  <? $title = _("Search partnership");
	 print_header_bar(true, "searchHelpPopup", $title); ?>
	
	<div data-role="content">
	
		<!-- ------------------ -->
		<!-- CONTENT -->
		<!-- ------------------ -->
		<br>
		<form action="index.php?action=main" method="POST" data-ajax="false">
			
			<input type="hidden" name="method" value="Search" />
			<input type="hidden" id="search_theme" name="theme" value="" />
			<input type="hidden" id="search_other" name="other" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("How to search") ?>?</h3>
				<?= _("<p>Enter a list of <strong>keywords</strong> corresponding to your projects search or partnerships.")?><br />
				<?= _("You can also use the options to filter your search or <strong>subscribe</strong> to a particular thematic.</p>")?>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Search a project') ?> :</h3>
			
	   				<fieldset data-role="controlgroup">
						<select id="search_theme_content" id="call">
						<option value=""><?= _("Themes") ?></option>
						<? foreach (Categories::$themes as $k=>$v): ?>
							<option value="<?= $k ?>">
								<?= $v ?>
							</option>
							<? endforeach; ?>
						</select>
						
						<select id="search_other_content" id="call">
							<option value=""><?= _("Program") ?></option>
							<? foreach (Categories::$calls as $k=>$v): ?>
								<option value="<?= $k ?>">
									<?= $v ?>
								</option>
							<? endforeach; ?>
						</select>
					</fieldset>
			
				<div style="text-align: center;">
					<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
						$('#search_theme').val($('#search_theme_content').val());
						$('#search_other').val($('#search_other_content').val());
					"/>
				</div>
			</div>
			
		</form>
			
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
			<h3><?= _('Our selection') ?> :</h3>
			<ul data-role="listview" data-filter="true" >
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Themes") ?></b>: <?= Categories::$themes[$item->theme] ?><br/>
							<b><?= _("Program") ?></b>: <?= Categories::$calls[$item->other] ?><br/><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							<p style="display:inline;" > Reputation: </p>  
							<p style="display:inline;" >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:80px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:80px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:80px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
								<p style="display:inline; margin-left:55px;  color: #2489CE; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> rates </p>
							</p>
						</p>
					</a>
				</li>
			<? endforeach ?>
			</ul>
		</div>
			
	</div>
	
	<!-- ----------------- -->
	<!-- SEARCH HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="searchHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<?= _("<h3>Operation of search engine:</h3> <p>If you leave all fields <b>empty</b>, you get all offers published to date.</p><p>When you leave an <b>empty</b> category, she is not consider in the research.</p><p>When you check / fill several fields in a category, results correspond to at least one of the criteria selected.</p>")?>
	</div>
	
</div>
