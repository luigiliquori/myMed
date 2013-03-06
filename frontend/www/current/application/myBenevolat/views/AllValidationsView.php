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
							<h3><?= _("Title")?> : <a data-ajax="false" href="?action=details&id=<?= $item->id ?>"><?= $item->title?></a></h3>
							<!-- Publication fields-->
							<p style="position: relative; margin-left: 30px;">
								<b><?= _('Publication date') ?></b>: <?= $item->begin ?><br/>
								<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/><br/>
								<b><?= _("Mission type") ?></b>: <?= Categories::$missions[$item->typeMission] ?><br/>
								<b><?= _("District") ?></b>: <?= Categories::$mobilite[$item->quartier] ?><br/>
								<b><?= _("Skills") ?></b>: 
							 <? if(gettype($item->competences)=="string"){ ?> <!-- only 1 skill -> string and not array -->
									<?= Categories::$competences[$item->competences]?><br/><br/>
							 <? }else{ ?>
									<? foreach($item->competences as $competences): echo Categories::$competences[$competences]." , "; endforeach;?><br/><br/>
							 <? } ?>
								<b><?= _("Association ID")?>:</b><?= $item->publisherID ?><br/> 
							</p>
						</div>
						<div class="ui-block-b">
							<div data-role="controlgroup" data-type="horizontal" style="float: right;">
							<?  $competences = "";
								if(gettype($item->competences)=="array") $competences = implode(ENUM_SEPARATOR, $item->competences);
								else $competences = $item->competences; ?>
								<a type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->id ?>","<?= $item->begin ?>","<?= $item->promue ?>","<?= $item->end ?>","<?= $item->text ?>","<?= $item->title ?>", "<?= $competences ?>", "<?= $item->typeMission ?>", "<?= $item->quartier ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Validate') ?></a>		
								<a type="button" href="#" onclick='generate_refuse_popup("<?= $item->id ?>","<?= $item->publisher ?>","<?= $item->title ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Delete') ?></a>
							</div>
						</div>
						<script type="text/javascript">
							function generate_accept_popup(publisher,id,begin,promue,end,text,title,competences,typeMission,quartier){
								$("#popupAccept").html('<p style="font-size:85%;"><?= _("You can attach a message to inform the association (or just click on Validate):") ?></p>\
									<form action="?action=validation&method=accept" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
										<input type="hidden" name="publisher" value="'+publisher+'" />\
						 				<input type="hidden" name="id" value="'+id+'" />\
										<input type="hidden" name="begin" value="'+begin+'" />\
										<input type="hidden" name="promue" value="'+promue+'" />\
						 				<input type="hidden" name="date" value="'+end+'" />\
						 				<input type="hidden" name="text" value="'+text+'" />\
						 				<input type="hidden" name="title" value="'+title+'" />\
						 			 	<input type="hidden" name="competences" value="'+competences+'" />\
						 				<input type="hidden" name="mission" value="'+typeMission+'" />\
						 				<input type="hidden" name="quartier" value="'+quartier+'" />\
						 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Validate') ?>" />\
						 			</form>\
						 			<a href="#" data-role="button" data-inline="true" data-mini="true" data-rel="back" data-direction="reverse"><?= _('Cancel') ?></a>\
									');
								$("#popupAccept").trigger('create');
					 			$("#popupAccept").popup("open");
							}

							function generate_refuse_popup(id,publisherID,title){
								$("#popupRefuse").html('<p style="font-size:85%;"><?= _("You can attach a message to inform the association (or just click on Delete):") ?></p>\
									<form action="?action=validation&method=refuse" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
						 				<input type="hidden" name="id" value="'+id+'" />\
						 				<input type="hidden" name="author" value="'+publisherID+'" />\
						 				<input type="hidden" name="title" value="'+title+'" />\
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