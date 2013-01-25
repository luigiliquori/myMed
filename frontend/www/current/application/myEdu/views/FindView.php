<!-- 2nd Page : Find/Subscribe -->

<div id="find" data-role="page">

	<? print_header_bar("?action=main", "defaultHelpPopup", "Search", 'myEdu Home'); ?>
	
	<div data-role="content">
	
		<form action="index.php?action=find" method="POST" data-ajax="false">
			<input type="hidden" id="find_area" name="area" value="" />
			<input type="hidden" id="find_category" name="category" value="" />
			<input type="hidden" id="find_organization" name="organization" value="" />
			<input type="hidden" id="find_locality" name="locality" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("How to find") ?>?</h3>
				<?= _("Here how to find")?>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Find a publication') ?> :</h3>
			
	   			<fieldset data-role="controlgroup">
					<!-- Categoria -->
					<select name="Category" id="find_category_content" id="call">
						<option value=""><?= _("Category") ?></option>
						<? foreach (Categories::$categories as $k=>$v) :?>
							<option value="<?= $k ?>"><?= $v ?></option>
						<? endforeach ?>
					</select>
				</fieldset>
			
				<!-- ADVANCED RESEARCH -->
				<div data-role="collapsible" data-collapsed="true" data-theme="a" data-content-theme="d" data-mini="true" style="margin-left:25px; margin-right:25px; margin-top:20px">
					<h3><?= _("Advanced research")?></h3>
					
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="organizationBox" id="check-view-a"/> <label for="check-view-a"><?= _("Organization")?></label>
						</div>
						<div class="ui-block-b">
							<select name="organization" id="find_organization_content" id="call" data-native-menu="false">
								<option value=""></option>
								<? foreach (Categories::$organizations as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="localityBox" id="check-view-b"/> <label for="check-view-b"><?= _("Locality")?></label>
						</div>
						<div class="ui-block-b">
							<select name="locality" id="find_locality_content" id="call" data-native-menu="false">
								<option value=""></option>
								<? foreach (Categories::$localities as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="areaBox" id="check-view-c"/> <label for="check-view-c"><?= _("Area")?></label>
						</div>
						<div class="ui-block-b">
							<select name="Area" id="find_area_content" id="call">
								<option value=""></option>
									<? foreach (Categories::$areas as $k=>$v) :?>
										<option value="<?= $k ?>"><?= $v ?></option>
									<? endforeach ?>
							</select>
						</div>
					</div>
				</div>
			
				<div style="text-align: center;">
					<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
						$('#find_area').val($('#find_area_content').val());
						$('#find_category').val($('#find_category_content').val());
						$('#find_locality').val($('#find_locality_content').val());
						$('#find_organization').val($('#find_organization_content').val());
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
							<b><?= _("Locality") ?></b>: <?= $item->locality ?><br/>
							<b><?= _("Category") ?></b>: <?= $item->category ?><br/>
							<b><?= _("Area") ?></b>: <?= $item->area ?><br/>
							<b><?= _("Organization") ?></b>: <?= $item->organization ?><br/><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p style="display:inline; margin-left: 30px;">
							Publisher ID: <?= $item->publisherID ?><br/>
							<!-- Project reputation-->	
							<p style="display:inline; margin-left: 30px;" > <b>Project Reputation:</b> </p>  
							<p style="display:inline; margin-left: 30px;" >
								<?php
									// Disable reputation stars if there are no votes yet 
									if($this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:175px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:175px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:175px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
								<p style="display:inline; margin-left:35px;  color: #2489CE; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> rates </p>
							</p>
						</p>
					</a>
				</li>
			<? endforeach ?>
			</ul>
		</div>
			
	</div>
</div>

