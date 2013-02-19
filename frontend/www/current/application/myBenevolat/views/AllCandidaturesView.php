<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="allcandidatureesview">


	<!-- Page header bar -->
	<? $title = _("All candidatures");
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
			
			<? if (count($this->result_apply) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
 			<?foreach ($this->result_apply as $item) :?>
 				<li>
	 				<fieldset class="ui-grid-c">
						<div class="ui-block-a">
							<?= _("Applier") ?>: <b><?= $item->publisher ?></b>
						</div>
						<div class="ui-block-b">
							<?= _("Annonce") ?>: <b><?= $item->title ?></b>
						</div>
						<div class="ui-block-c">
							<?= _("Author ") ?>: <b><?= $item->author ?></b>
						</div>
						<div class="ui-block-d">
							<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
							<div data-role="controlgroup" data-type="horizontal" style="float: right;">
							 <? if($item->accepted!='accepted'): ?>
									<a type="button" href="#" onclick='generate_accept_popup("<?= $item->publisher ?>","<?= $item->pred1 ?>","<?= $item->pred2 ?>","<?= $item->idAnnonce ?>","<?= $item->author ?>","<?= $item->title ?>");' data-theme="g" data-inline="true" data-mini="true"><?= _('Accept') ?></a>
							<?  endif; ?>			
								<a type="button" href="#" onclick='generate_refuse_popup("<?= $item->publisher ?>","<?= $item->pred1 ?>","<?= $item->pred2 ?>","<?= $item->idAnnonce ?>","<?= $item->author ?>","<?= $item->title ?>","<?= $item->accepted ?>");' data-theme="r" data-inline="true" data-mini="true"><?= _('Refuse') ?></a>
							</div>
							<script type="text/javascript">
								function generate_accept_popup(publisher, pred1,pred2,idAnnonce,author,title){
									$("#popupAccept").html('<?= _("You can attach a message for the applier:") ?>\
										<form action="?action=apply&method=accept" method="POST" data-ajax="false">\
				 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
							 				<input type="hidden" name="publisher" value="'+publisher+'" />\
							 				<input type="hidden" name="pred1" value="'+pred1+'" />\
							 				<input type="hidden" name="pred2" value="'+pred2+'" />\
							 				<input type="hidden" name="id" value="'+idAnnonce+'" />\
							 				<input type="hidden" name="author" value="'+author+'" />\
							 				<input type="hidden" name="title" value="'+title+'" />\
							 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Send') ?>" />\
							 			</form>');
									$("#popupAccept").trigger('create');
						 			$("#popupAccept").popup("open");
								}

								function generate_refuse_popup(publisher, pred1,pred2,idAnnonce,author,title,accepted){
									$("#popupRefuse").html('<?= _("You can attach a message for the applier:") ?>\
										<form action="?action=apply&method=refuse" method="POST" data-ajax="false">\
				 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>\
							 				<input type="hidden" name="publisher" value="'+publisher+'" />\
							 				<input type="hidden" name="pred1" value="'+pred1+'" />\
							 				<input type="hidden" name="pred2" value="'+pred2+'" />\
							 				<input type="hidden" name="id" value="'+idAnnonce+'" />\
							 				<input type="hidden" name="author" value="'+author+'" />\
							 				<input type="hidden" name="title" value="'+title+'" />\
							 				<input type="hidden" name="accepted" value="'+accepted+'" />\
							 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Send') ?>" />\
							 			</form>');
									$("#popupRefuse").trigger('create');
							 		$("#popupRefuse").popup("open");
								}
							</script>
							<div data-role="popup" id="popupAccept" class="ui-content" Style="text-align: center; width: 18em;"></div>
							<div data-role="popup" id="popupRefuse" class="ui-content" Style="text-align: center; width: 18em;"></div>
						</div>
					</fieldset>
 				</li>
			<? endforeach ?>
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->