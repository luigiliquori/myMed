<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="allvalidationsview">


	<!-- Page header bar -->
	<? $title = _("Manage validations");
	   print_header_bar("?action=main", "defaultViewHelpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Manage validations capsule title") ?></h3>
			<p><?= _("Manage validations capsule text")?></p>
		</div>
		<br />
		
		<ul data-role="listview" data-filter="false" >
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
 			<?foreach ($this->result as $item) :?>
 				<li>
	 				<div class="ui-grid-a">
						<div class="ui-block-a">
							<h3><?= _("Title")?> : <a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>"><?= $item->getTitle(); ?></a></h3>
							<!-- Publication fields-->
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
							</p>
							
						</div>
						<div class="ui-block-b">
							<div data-role="controlgroup" data-type="horizontal" style="float: right;">
								<a type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->begin ?>","<?= $item->expire_date ?>","<?= $item->data ?>", "<?= $item->Nazione ?>", "<?= $item->Lingua ?>", "<?= (isset($item->Arte_Cultura))?'on':'off' ?>", "<?= (isset($item->Natura))?'on':'off' ?>", "<?= (isset($item->Tradizioni))?'on':'off' ?>", "<?= (isset($item->Enogastronomia))?'on':'off' ?> ", "<?= (isset($item->Benessere))?'on':'off' ?>" , "<?= (isset($item->Storia))?'on':'off' ?>" , "<?= (isset($item->Religione))?'on':'off' ?>", "<?= (isset($item->Escursioni_Sport))?'on':'off' ?>", "<?= $item->getTitle() ?>", "<?= $item->text ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Validate') ?></a>		
								<a type="button" href="#" onclick='generate_refuse_popup("<?= $item->publisher ?>","<?= $item->begin ?>","<?= $item->expire_date ?>","<?= $item->data ?>", "<?= $item->Nazione ?>", "<?= $item->Lingua ?>", "<?= (isset($item->Arte_Cultura))?'on':'off' ?>", "<?= (isset($item->Natura))?'on':'off' ?>", "<?= (isset($item->Tradizioni))?'on':'off' ?>", "<?= (isset($item->Enogastronomia))?'on':'off' ?> ", "<?= (isset($item->Benessere))?'on':'off' ?>" , "<?= (isset($item->Storia))?'on':'off' ?>" , "<?= (isset($item->Religione))?'on':'off' ?>", "<?= (isset($item->Escursioni_Sport))?'on':'off' ?>", "<?= $item->getTitle() ?>", "<?= $item->text ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Delete') ?></a>
							</div>
						</div>
						<script type="text/javascript">
							function generate_accept_popup(publisher,begin,expire_date, data, Nazione, Lingua, Arte_Cultura, Natura, Tradizioni, Enogastronomia, Benessere, Storia, Religione, Escursioni_Sport, data, text){
								$("#popupAccept").html('<p style="font-size:85%;"><?= _("You can attach a message for the applier (or just click on Validate):") ?></p>\
									<form action="?action=validation&method=accept" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
										<input type="hidden" name="publisher" value="'+publisher+'" />\
										<input type="hidden" name="begin" value="'+begin+'" />\
						 				<input type="hidden" name="expire_date" value="'+expire_date+'" />\
						 				<input type="hidden" name="data" value="'+data+'" />\
						 			 	<input type="hidden" name="Nazione" value="'+Nazione+'" />\
						 			 	<input type="hidden" name="Lingua" value="'+Lingua+'" />\
						 			 	<input type="hidden" name="Arte_Cultura" value="'+Arte_Cultura+'" />\
						 			 	<input type="hidden" name="Natura" value="'+Natura+'" />\
						 			 	<input type="hidden" name="Tradizioni" value="'+Tradizioni+'" />\
						 			 	<input type="hidden" name="Enogastronomia" value="'+Enogastronomia+'" />\
						 			 	<input type="hidden" name="Benessere" value="'+Benessere+'" />\
						 			 	<input type="hidden" name="Storia" value="'+Storia+'" />\
						 			 	<input type="hidden" name="Religione" value="'+Religione+'" />\
						 			 	<input type="hidden" name="Escursioni_Sport" value="'+Escursioni_Sport+'" />\
						 			 	<input type="hidden" name="data" value="'+data+'" />\
						 			 	<input type="hidden" name="text" value="'+text+'" />\
						 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Validate') ?>" />\
						 			</form>\
						 			<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>\
							 			');
								$("#popupAccept").trigger('create');
					 			$("#popupAccept").popup("open");
							}

							function generate_refuse_popup(publisher,begin,expire_date, data, Nazione, Lingua, Arte_Cultura, Natura, Tradizioni, Enogastronomia, Benessere, Storia, Religione, Escursioni_Sport, data, text){
								$("#popupRefuse").html('<p style="font-size:85%;"><?= _("You can attach a message for the applier (or just click on Delete):") ?></p>\
									<form action="?action=validation&method=refuse" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
			 	    					<input type="hidden" name="publisher" value="'+publisher+'" />\
										<input type="hidden" name="begin" value="'+begin+'" />\
						 				<input type="hidden" name="expire_date" value="'+expire_date+'" />\
						 				<input type="hidden" name="data" value="'+data+'" />\
						 			 	<input type="hidden" name="Nazione" value="'+Nazione+'" />\
						 			 	<input type="hidden" name="Lingua" value="'+Lingua+'" />\
						 			 	<input type="hidden" name="Arte_Cultura" value="'+Arte_Cultura+'" />\
						 			 	<input type="hidden" name="Natura" value="'+Natura+'" />\
						 			 	<input type="hidden" name="Tradizioni" value="'+Tradizioni+'" />\
						 			 	<input type="hidden" name="Enogastronomia" value="'+Enogastronomia+'" />\
						 			 	<input type="hidden" name="Benessere" value="'+Benessere+'" />\
						 			 	<input type="hidden" name="Storia" value="'+Storia+'" />\
						 			 	<input type="hidden" name="Religione" value="'+Religione+'" />\
						 			 	<input type="hidden" name="Escursioni_Sport" value="'+Escursioni_Sport+'" />\
						 			 	<input type="hidden" name="data" value="'+data+'" />\
						 			 	<input type="hidden" name="text" value="'+text+'" />\
						 				<input data-role="button" type="submit" data-theme="r" data-inline="true" data-icon="ok" value="<?= _('Delete') ?>" />\
						 			</form>\
						 			<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>\
						 			');
								$("#popupRefuse").trigger('create');
						 		$("#popupRefuse").popup("open");
							}
						</script>
						<div data-role="popup" id="popupAccept" class="ui-content" Style="text-align: center; width: 18em;"></div>
						<div data-role="popup" id="popupRefuse" class="ui-content" Style="text-align: center; width: 18em;"></div>
					</div>
 				</li>
			<? endforeach ?>
		</ul>
			
	</div>
	<!-- END Page content -->
	<!-- Help popup -->
	<div data-role="popup" id="defaultViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Manage validations help text") ?></p>
	</div>
</div>
<!-- END Page MyPublicationView-->