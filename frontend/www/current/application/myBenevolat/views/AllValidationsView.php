<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="allvalidationsview">


	<!-- Page header bar -->
	<? $title = _("All validations");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("<<<<< Little help >>>>>") ?> ?</h3>
			<p><?= _("Here is the space summarizing all of your candidatures.")?></p>
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
							<h3><?= _("Title")?> : <?= $item->title ?></h3>
							<!-- Publication fields-->
							<p style="position: relative; margin-left: 30px;">
								<b><?= _('Date of publication') ?></b>: <?= $item->begin ?><br/>
								<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/><br/>
								<b><?= _("Mission type") ?></b>: <?= Categories::$missions[$item->typeMission] ?><br/>
								<b><?= _("Quartier") ?></b>: <?= Categories::$mobilite[$item->quartier] ?><br/>
								<b><?= _("Competences") ?></b>: 
							 <? if(gettype($item->competences)=="string"){ ?> <!-- only 1 skill -> string and not array -->
									<?= Categories::$competences[$item->competences]?><br/><br/>
							 <? }else{ ?>
									<? foreach($item->competences as $competences): echo Categories::$competences[$competences]." , "; endforeach;?><br/><br/>
							 <? } ?>
								<b>Association ID:</b><?= $item->publisherID ?><br/> 
							</p>
						</div>
						<div class="ui-block-b">
							<div data-role="controlgroup" data-type="horizontal" style="float: right;">
							<?  $competences = "";
								if(gettype($item->competences)=="array") $competences = implode(ENUM_SEPARATOR, $item->competences);
								else $competences = $item->competences; ?>
								<a type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->id ?>","<?= $item->begin ?>","<?= $item->promue ?>","<?= $item->end ?>","<?= $item->text ?>","<?= $item->title ?>", "<?= $competences ?>", "<?= $item->typeMission ?>", "<?= $item->quartier ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Valider') ?></a>		
								<a type="button" href="#" onclick='generate_refuse_popup("<?= $item->getPredicateStr() ?>","<?= $item->publisher ?>","<?= $item->title ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Supprimer') ?></a>
							</div>
						</div>
						<script type="text/javascript">
							function generate_accept_popup(publisher,id,begin,promue,end,text,title,competences,typeMission,quartier){
								$("#popupAccept").html('<?= _("You can attach a message for the applier:") ?>\
									<form action="?action=validation&method=accept" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
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
						 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Send') ?>" />\
						 			</form>');
								$("#popupAccept").trigger('create');
					 			$("#popupAccept").popup("open");
							}

							function generate_refuse_popup(predicate,publisherID,title){
								$("#popupRefuse").html('<?= _("You can attach a message for the applier:") ?>\
									<form action="?action=validation&method=refuse" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
						 				<input type="hidden" name="predicate" value="'+predicate+'" />\
						 				<input type="hidden" name="author" value="'+publisherID+'" />\
						 				<input type="hidden" name="title" value="'+title+'" />\
						 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Send') ?>" />\
						 			</form>');
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
	
</div>
<!-- END Page MyPublicationView-->