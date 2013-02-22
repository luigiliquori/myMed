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
							<h3><?= _("Title")?> : <?= $item->title ?></h3>
							<!-- Publication fields-->
							<p style="position: relative; margin-left: 30px;"> 
								<!-- <b><?= _('Publication date') ?></b>: <?= $item->begin ?><br/> -->
								<b><?= _('Deadline') ?></b>: <?= $item->end ?><br/><br/>
								<b><?= _("Locality") ?></b>: <?= Categories::$localities[$item->locality] ?><br/>
								<b><?= _("Language") ?></b>: <?= Categories::$languages[$item->language] ?><br/>
								<b><?= _("Category") ?></b>: <?= Categories::$categories[$item->category] ?><br/>
								 
								<b><?= _("Publisher ID")?>:</b><?= $item->publisherID ?><br/> 
							</p>
						</div>
						<div class="ui-block-b">
							<div data-role="controlgroup" data-type="horizontal" style="float: right;">
							<?  $competences = "";
								if(gettype($item->competences)=="array") $competences = implode(ENUM_SEPARATOR, $item->competences);
								else $competences = $item->competences; ?>
								<a type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->id ?>","<?= $item->begin ?>","<?= $item->end ?>","<?= $item->text ?>","<?= $item->title ?>", "<?= $item->locality ?>", "<?= $item->language?>", "<?= $item->category ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Validate') ?></a>		
								<a type="button" href="#" onclick='generate_refuse_popup("<?= $item->getPredicateStr() ?>","<?= $item->publisher ?>","<?= $item->title ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Delete') ?></a>
							</div>
						</div>
						<script type="text/javascript">
							function generate_accept_popup(publisher,id,begin,end,text,title,locality,language,category){
								$("#popupAccept").html('<?= _("You can attach a message for the applier:") ?>\
									<form action="?action=validation&method=accept" method="POST" data-ajax="false">\
			 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
										<input type="hidden" name="publisher" value="'+publisher+'" />\
						 				<input type="hidden" name="id" value="'+id+'" />\
										<input type="hidden" name="begin" value="'+begin+'" />\
						 				<input type="hidden" name="date" value="'+end+'" />\
						 				<input type="hidden" name="text" value="'+text+'" />\
						 				<input type="hidden" name="title" value="'+title+'" />\
						 			 	<input type="hidden" name="locality" value="'+locality+'" />\
						 			 	<input type="hidden" name="language" value="'+language+'" />\
						 			 	<input type="hidden" name="category" value="'+category+'" />\
						 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('OK') ?>" />\
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
						 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('OK') ?>" />\
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
	<!-- Help popup -->
	<div data-role="popup" id="defaultViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Manage validations help text") ?></p>
	</div>
</div>
<!-- END Page MyPublicationView-->