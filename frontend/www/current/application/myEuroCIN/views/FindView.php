<!-- ------------------------------------ -->
<!-- Find Main View                       -->
<!-- Here you can search for publications -->
<!-- ------------------------------------ -->


<!-- Page view -->
<div data-role="page" id="find" >

	<!-- Header bar -->
	<? $title = _("Search"); 
	   print_header_bar("?action=main", "findViewHelpPopup", $title); ?>

	
	<!-- Page content -->
	<div data-role="content">
		
		<form action="index.php?action=find" method="POST" data-ajax="false">
			<input type="hidden" id="find_locality" name="locality" value="" />
			<input type="hidden" id="find_language" name="language" value="" />
			<input type="hidden" id="find_category" name="category" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("How to find") ?>?</h3>
				<?= _("Specify properties to retrieve related publications.")?>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Find a publication') ?> :</h3>
			
	   			<fieldset data-role="controlgroup">
					<!-- Categoria -->
					<select name="locality" id="find_locality_content" id="call" data-native-menu="false">
						<option value=""><?= _("Locality") ?></option>
						<? foreach (Categories::$localities as $k=>$v) :?>
							<option value="<?= $k ?>"><?= $v ?></option>
						<? endforeach ?>
					</select>
				</fieldset>
			
				<!-- ADVANCED RESEARCH -->
				<div data-role="collapsible" data-collapsed="true" data-theme="a" data-content-theme="d" data-mini="true" style="margin-left:25px; margin-right:25px; margin-top:20px">
					<h3><?= _("Advanced research")?></h3>
					
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="languageBox" id="check-view-a"/> <label for="check-view-a"><?= _("Language")?></label>
						</div>
						<div class="ui-block-b">
							<select name="language" id="find_language_content" id="call" data-native-menu="false">
								<option value=""></option>
								<? foreach (Categories::$languages as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="categoryBox" id="check-view-c"/> <label for="check-view-c"><?= _("Category")?></label>
						</div>
						<div class="ui-block-b">
							<select name="category" id="find_category_content" id="call" data-native-menu="false">
								<option value=""></option>
									<? foreach (Categories::$categories as $k=>$v) :?>
										<option value="<?= $k ?>"><?= $v ?></option>
									<? endforeach ?>
							</select>
						</div>
					</div>
				</div>
			
				<div style="text-align: center;">
					<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
						$('#find_locality').val($('#find_locality_content').val());
						$('#find_language').val($('#find_language_content').val());
						$('#find_category').val($('#find_category_content').val());
					"/>
				</div>
			</div>
		</form>
		
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
			<h3><?= _('Last publications') ?> :</h3>
			<ul data-role="listview" data-filter="true" >
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Locality") ?></b>: <?= _($item->locality) ?><br/>
							<b><?= _("Language") ?></b>: <?= _($item->language) ?><br/>
							<b><?= _("Category") ?></b>: <?= _($item->category) ?><br/><br/>
							<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p style="display:inline; margin-left: 30px;">
							Publisher ID: <?= $item->publisherID ?><br/>
							<!-- Project reputation-->	
							<p style="display:inline; margin-left: 30px;" > <b><?= _("Reputation")?>:</b> </p>  
							<p style="display:inline; margin-left: 30px;" >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="margin-left: <?= $i ?>0px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
								<p style="display:inline; margin-left:70px;  color: #2489CE; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> <?= _("rates")?> </p>
							</p>
						</p>
					</a>
				</li>
			<? endforeach ?>
			</ul>
		</div>
			
	</div>
	
	<!-- Help popup -->
	<div data-role="popup" id="findViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Use search parameters to find publications that are interesting for you.") ?></p>
	</div>
	
</div>

