<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="allcandidatureesview">


	<!-- Page header bar -->
	<? $title = _("Manage candidatures");
	   print_header_bar("?action=main", "defaultViewHelpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Manage candidatures capsule title") ?></h3>
			<p><?= _("Manage candidatures capsule text")?></p>
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
							<?= _("Applier") ?>: <b><a href="?action=extendedProfile&method=show_user_profile&user=<?= $item->publisher?>"><?= $item->publisherName ?></a></b>
						</div>
						<div class="ui-block-b">
							<?= _("Annonce") ?>: <b><a href="?action=details&id=<?= $item->idAnnonce ?>"><?= $item->title ?></a></b>
						</div>
						<div class="ui-block-c">
							<?= _("Author ") ?>: <b><a href="?action=extendedProfile&method=show_user_profile&user=<?= $item->author?>"><?= substr($item->author,6) ?></a></b>
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
									$("#popupAccept").html('<p style="font-size:85%;"><?= _("You can attach a message for the applier (or just click on Accept):") ?></p>\
										<form action="?action=apply&method=accept" method="POST" data-ajax="false">\
				 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
							 				<input type="hidden" name="publisher" value="'+publisher+'" />\
							 				<input type="hidden" name="pred1" value="'+pred1+'" />\
							 				<input type="hidden" name="pred2" value="'+pred2+'" />\
							 				<input type="hidden" name="id" value="'+idAnnonce+'" />\
							 				<input type="hidden" name="author" value="'+author+'" />\
							 				<input type="hidden" name="title" value="'+title+'" />\
							 				<input data-role="button" type="submit" data-theme="g" data-inline="true" data-icon="ok" value="<?= _('Accept') ?>" />\
							 			</form>\
								 		<a href="#" data-role="button" data-mini="true" data-inline="true" data-rel="back" data-direction="reverse"><?= _("Cancel") ?></a>\
										');
									$("#popupAccept").trigger('create');
						 			$("#popupAccept").popup("open");
								}

								function generate_refuse_popup(publisher, pred1,pred2,idAnnonce,author,title,accepted){
									$("#popupRefuse").html('<p style="font-size:85%;"><?= _("You can attach a message for the applier (or just click on Refuse):") ?></p>\
										<form action="?action=apply&method=refuse" method="POST" data-ajax="false">\
				 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea>\
							 				<input type="hidden" name="publisher" value="'+publisher+'" />\
							 				<input type="hidden" name="pred1" value="'+pred1+'" />\
							 				<input type="hidden" name="pred2" value="'+pred2+'" />\
							 				<input type="hidden" name="id" value="'+idAnnonce+'" />\
							 				<input type="hidden" name="author" value="'+author+'" />\
							 				<input type="hidden" name="title" value="'+title+'" />\
							 				<input type="hidden" name="accepted" value="'+accepted+'" />\
							 				<input data-role="button" type="submit" data-theme="r" data-inline="true" data-icon="ok" value="<?= _('Refuse') ?>" />\
							 			</form>\
							 			<a href="#" data-role="button" data-mini="true" data-inline="true" data-rel="back" data-direction="reverse"><?= _("Cancel") ?></a>\
											');
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
	<!-- Help popup -->
	<div data-role="popup" id="defaultViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Manage candidatures help text") ?></p>
	</div>
</div>
<!-- END Page MyPublicationView-->