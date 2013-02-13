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
							<?= _("De ") ?>: <b><?= $item->author ?></b>
						</div>
						<div class="ui-block-d">
							<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
							<div data-role="controlgroup" data-type="horizontal" style="float: right;">
							 <? if($item->accepted!='accepted'): ?>
								<!-- <a style="float:left" type="button" href="#popupAccept" data-rel="popup" data-theme="g" data-inline="true" data-mini="true"><?= _('Accept') ?></a> -->
									<form action="?action=apply&method=accept" method="POST" data-ajax="false" style="float:left">
			 	    				<!-- <textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br> -->
						 				<input type="hidden" name="publisher" value="<?= $item->publisher ?>" />
						 				<input type="hidden" name="pred1" value="<?= $item->pred1 ?>" />
						 				<input type="hidden" name="pred2" value="<?= $item->pred2 ?>" />
						 				<input type="hidden" name="pred3" value="<?= $item->pred3 ?>" />
						 				<input type="hidden" name="author" value="<?= $item->author ?>" />
						 				<input type="hidden" name="title" value="<?= $item->title ?>" />
						 				
										<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Accept') ?>" />
						 			</form>
								
							 <? endif; ?>
							 <form action="?action=apply&method=refuse" method="POST" data-ajax="false" style="float:right">
							<!-- 	<textarea id="msgMail" name="msgMail" style="height: 120px;"></textarea><br> -->
					 				<input type="hidden" name="publisher" value="<?= $item->publisher ?>" />
					 				<input type="hidden" name="pred1" value="<?= $item->pred1 ?>" />
					 				<input type="hidden" name="pred2" value="<?= $item->pred2 ?>" />
					 				<input type="hidden" name="pred3" value="<?= $item->pred3 ?>" />
					 				<input type="hidden" name="title" value="<?= $item->title ?>" />
					 				<input type="hidden" name="author" value="<?= $item->author ?>" />
					 				<input type="hidden" name="accepted" value="<?= $item->accepted ?>" />
					 				
									<input data-role="button" type="submit" data-theme="r" data-inline="true" data-mini="true" value="<?= _('Refuse') ?>" />
						 		</form>
							<!-- <a style="float:right" type="button" href="#popupRefuse" data-rel="popup" data-theme="r" data-inline="true" data-mini="true"><?= _('Refuse') ?></a> -->
							</div>
						</div>
					</fieldset>
 				</li>
			<? endforeach ?>
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->