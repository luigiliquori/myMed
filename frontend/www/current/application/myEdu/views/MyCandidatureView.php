<!-- ------------------ -->
<!-- MyPublication View -->
<!-- ------------------ -->

<div data-role="page" id ="mycandidatureesview">

	<!-- Page header bar -->
	<? $title = _("My offer's applications");
	   print_header_bar("?action=main", "helpPopup", $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("My offer's applications") ?></h3>
			<p><?= _("Check your applications and see whether you have been accepted.")?></p>
		</div>
		<br />
	
		<!-- List of student courses -->
		<ul data-role="listview" >
		
			<li data-role="list-divider"><?= _("Results") ?></li>
			
			<? if (count($this->result) == 0) :?>
				<li>
					<h4><?= _("No result found")?></h4>
				</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
				<li>
					<a data-ajax="false" href="?action=details&predicate=<?= $item->pred3 ?>&author=<?= $item->author ?>">			
						<div class="ui-grid-b">
							<div class="ui-block-a">
								<?= _("Publication name") ?>: <b><?= $item->title ?></b>
							</div>
							<div class="ui-block-b">
								<?= _("Author email") ?>: <b><?= substr($item->author,6) ?></b>
								
							</div>
							<div class="ui-block-c">
								<div style="float: right;">
									<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
								</div>
							</div>
						</div>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		<!-- HELP POPUP -->
		<!-- ----------------- -->
		<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<p><?= _("Check your applications and see whether you have been accepted.")?></p>
		</div>	
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->