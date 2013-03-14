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
						<option value=""><?= _("Select locality") ?></option>
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
							<input type="checkbox" name="languageBox" onclick="toggle(this, '#find_language_content')" id="check-view-a" /> 
							<label for="check-view-a"><?= _("Language")?></label>
						</div>
						<div class="ui-block-b">
							<select disabled name="language" id="find_language_content" id="call" data-native-menu="false">
								<option value=""><?= _("Select language")?></option>
								<? foreach (Categories::$languages as $k=>$v) :?>
									<option value="<?= $k ?>"><?= $v ?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
			
					<!-- Categories -->
					<fieldset data-role="controlgroup">
					<? foreach (Categories::$categories as $k=>$v) :?>
					   	<input type="checkbox" name="<?= $k ?>" id="<?= $k ?>" class="custom" value="on">
					   	<label for="<?= $k ?>"><?= $v ?></label>
	   				<? endforeach ?>			   
				    </fieldset>
					
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
						<h3><?= _("Title")?> : <?= $item->data ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Locality") ?></b>: <?= Categories::$localities[$item->Nazione] ?><br/>
							<b><?= _("Language") ?></b>: <?= Categories::$languages[$item->Lingua] ?><br/>
							<b><?= _("Categories") ?></b>: 
							<? if( isset($item->Arte_Cultura) ) echo _("Art/Cultur "); ?> 
							<? if( isset($item->Natura) ) echo _("Nature "); ?>
							<? if( isset($item->Tradizioni) ) echo _("Traditions "); ?>
							<? if( isset($item->Enogastronomia) ) echo _("Enogastronimy "); ?>
							<? if( isset($item->Benessere) ) echo _("Wellness "); ?>
							<? if( isset($item->Storia) ) echo _("History "); ?>
							<? if( isset($item->Religione) ) echo _("Religion "); ?>
							<? if( isset($item->Escursioni_Sport) ) echo _("Sport "); ?>
						
						<br/>
						
						<p style="display:inline; margin-left: 30px;">
							Publisher ID: <?= $item->publisherID ?><br/>
							<!-- Project reputation-->	
							<p style="display:inline; margin-left: 30px;" > <b><?= _("Reputation")?>:</b> </p>  
							<p style="display:inline;" >
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
								<p style="display:inline; margin-left:70px; font-size:80%;"> <?php echo $this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] ?> <?= _("rates")?> </p>
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

