<!-- 2nd Page : Find/Subscribe -->
<? require_once('header-bar.php'); ?>

<div id="findview" data-role="page">

	<? $title = _("Search offer"); 
	   print_header_bar("?action=main", "findViewHelpPopup", $title); ?>

	<div data-role="content">
		
		<form name="frm" action="index.php?action=find" method="POST" data-ajax="false">
			<input type="hidden" id="find_area" name="area" value="" />
			<input type="hidden" id="find_category" name="category" value="" />
			<input type="hidden" id="find_organization" name="organization" value="" />
			<input type="hidden" id="find_locality" name="locality" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("Search offer")?></h3>
				<?= _("View the last offers and search offers according to your criteria.")?>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Find an offer by criteria') ?> :</h3>
			
	   			<fieldset data-role="controlgroup">
					<!-- Categoria -->
					<select name="Category" id="find_category_content" data-native-menu="false" data-overlay-theme="d">
						<option value=""><?= _("Select category") ?></option>
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
							<input type="checkbox" name="organizationBox" id="check-view-a" onclick="toggle(this, '#find_organization_content')"/> 
							<label for="check-view-a"><?= _("Offering organization")?></label>
						</div>
						<div class="ui-block-b">
							<select disabled name="organization" id="find_organization_content" data-native-menu="false" data-overlay-theme="d">
								<option value=""><?= _('Select organization') ?></option>
								<? foreach (Categories::$organizations as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" onclick="toggle(this, '#find_locality_content')" name="localityBox" id="check-view-b"/> 
							<label for="check-view-b"><?= _("Offer locality")?></label>
						</div>
						<div class="ui-block-b">
							<select disabled name="locality" id="find_locality_content" data-native-menu="false" data-overlay-theme="d">
								<option value=""><?= _('Select locality') ?></option>
								<? foreach (Categories::$localities as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" onclick="toggle(this, '#find_area_content')" name="areaBox" id="check-view-c"/> 
							<label for="check-view-c"><?= _("Topic")?></label>
						</div>
						<div class="ui-block-b" data-theme="d">
							<select disabled name="Area" id="find_area_content" data-native-menu="false" data-overlay-theme="d">
								<option value=""> <?= _("Select topic")?></option>
								<?  foreach(Categories::$areas as $k=>$v) :?>
										<optgroup label="<?= _($k) ?>">
										<?  foreach($v as $item) :?>
												<option value="<?= $item ?>"><?= _($item) ?></option>
										<?  endforeach;?>
										</optgroup>
								<?  endforeach ?>
							</select>
						</div>
					</div>
					<script type="text/javascript"> 	
						function toggle(chkbox, id) {
						    if(chkbox.checked){
							    $(id).selectmenu('enable');
							}else {
								$(id).selectmenu("disable");
							}
						}
					</script>
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
			<h3><?= _('Last offers') ?> :</h3>
			<ul data-role="listview" data-filter="true" >
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<? $domain="Not defined";
							foreach(Categories::$areas as $k=>$v) :
								if(in_array($item->area, $v)){
									$domain=$k;
									break;
								}
							endforeach; ?>
							<b><?= _("Topic") ?></b>: <?= _($item->area) ?><br/>
							<b><?= _("Domain")?></b>: <?= _($domain) ?><br/>
							<b><?= _("Category") ?></b>: <?= Categories::$categories[$item->category] ?><br/>
							<b><?= _("Locality") ?></b>: <?= Categories::$localities[$item->locality] ?><br/>
							<b><?= _("Organization") ?></b>: <?= Categories::$organizations[$item->organization] ?><br/><br/>
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
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:130px; margin-top:3px;"/>
									<?php } ?>
								<?php else: ?>
									<?php for($i=1 ; $i <= 5 ; $i++) { ?>
										<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
											<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:130px; margin-top:3px;" />
										<?php } else { ?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-left:130px; margin-top:3px;"/>
										<?php } ?>
									<? } ?>
								<?php endif; ?>
								<p style="display:inline; margin-left:35px; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> <?= _("rates")?> </p>
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
		<p> <?= _("View the last offers and search offers according to your criteria.") ?></p>
	</div>
	
</div>

