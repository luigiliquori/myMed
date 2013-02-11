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
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("No result found")?></h4>
			</li>
			<? endif ?>
 			<?foreach ($this->result as $item) :?>
 				<li>
	 				<div class="ui-grid-b">
						<div class="ui-block-a">
							<?= _("Applier") ?>: <b><?= $item->publisherName ?></b>
						</div>
						<div class="ui-block-b">
							<?= _("Annonce") ?>: <b><?= $item->title ?></b>
					</div>
					<div class="ui-block-c">
						<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
						<div data-role="controlgroup" data-type="horizontal" style="float: right;">
							<? if($item->accepted!='accepted'): ?>
								<a style="float:left" type="button" href="#popupAccept" data-rel="popup" data-theme="g" data-inline="true" data-mini="true"><?= _('Accept') ?></a>
							<? endif; ?>
							<a style="float:right" type="button" href="#popupRefuse" data-rel="popup" data-theme="r" data-inline="true" data-mini="true"><?= _('Refuse') ?></a>
						</div>
						
						
						<div data-role="popup" id="popupAccept" class="ui-content" Style="text-align: center; width: 18em;">
							<?= _("You can attach a message for the applier:") ?>
							
	 	    				<form action="?action=apply&method=accept" method="POST" data-ajax="false">
	 	    					<textarea id="msgMail" name="msgMail" style="height: 120px;" ></textarea><br>
				 				<input type="hidden" name="method" value="<?= _('Accept')?>" />
				 				<input type="hidden" name="publisher" value="<?= $item->publisher ?>" />
				 				<input type="hidden" name="pred1" value="<?= $item->pred1 ?>" />
				 				<input type="hidden" name="pred2" value="<?= $item->pred2 ?>" />
				 				<input type="hidden" name="pred3" value="<?= $item->pred3 ?>" />
				 				<input type="hidden" name="author" value="<?= $item->author ?>" />
				 				<input type="hidden" name="maxappliers" value="<?= $this->result->maxappliers ?>" />
				 				<input type="hidden" name="currentappliers" value="<?= $this->result->currentappliers ?>" />
				 				<input type="hidden" name="area" value="<?= $this->result->area ?>" />
				 				<input type="hidden" name="category" value="<?= $this->result->category ?>" />
				 				<input type="hidden" name="locality" value="<?= $this->result->locality ?>" />
				 				<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
				 				<input type="hidden" name="date" value="<?= $this->result->end ?>" />
				 				<input type="hidden" name="text" value="<?= $this->result->text ?>" />
				 				<input type="hidden" name="title" value="<?= $item->title ?>" />
				 				
								<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Send') ?>" />
				 			</form>
						</div>
						
						<div data-role="popup" id="popupRefuse" class="ui-content" Style="text-align: center; width: 18em;">
							<?= _("You can attach a message for the applier:") ?>
							<form action="?action=apply&method=refuse" method="POST" data-ajax="false">
								<textarea id="msgMail" name="msgMail" style="height: 120px;"></textarea><br>
				 				<input type="hidden" name="method" value="<?= _('Refuse')?>" />
				 				<input type="hidden" name="publisher" value="<?= $item->publisher ?>" />
				 				<input type="hidden" name="pred1" value="<?= $item->pred1 ?>" />
				 				<input type="hidden" name="pred2" value="<?= $item->pred2 ?>" />
				 				<input type="hidden" name="pred3" value="<?= $item->pred3 ?>" />
				 				<input type="hidden" name="title" value="<?= $item->title ?>" />
				 				<input type="hidden" name="author" value="<?= $item->author ?>" />
				 				<input type="hidden" name="maxappliers" value="<?= $this->result->maxappliers ?>" />
				 				<input type="hidden" name="currentappliers" value="<?= $this->result->currentappliers ?>" />
				 				<input type="hidden" name="area" value="<?= $this->result->area ?>" />
				 				<input type="hidden" name="category" value="<?= $this->result->category ?>" />
				 				<input type="hidden" name="locality" value="<?= $this->result->locality ?>" />
				 				<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
				 				<input type="hidden" name="date" value="<?= $this->result->end ?>" />
				 				<input type="hidden" name="text" value="<?= $this->result->text ?>" />
				 				<input type="hidden" name="accepted" value="<?= $item->accepted ?>" />
				 				
								<input data-role="button" type="submit" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Send') ?>" />
					 			</form>
							</div>
						</div>
					</div>
 				</li>
			<? endforeach ?>
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->