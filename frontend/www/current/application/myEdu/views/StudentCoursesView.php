<!-- ------------------ -->
<!-- MyPublication View -->
<!-- ------------------ -->

<div data-role="page" id ="studentcoursesview">

	<!-- Page header bar -->
	<? $title = _("My courses");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("<<<<< Little help >>>>>") ?> ?</h3>
			<p><?= _("Here is the space summarizing all of your courses.")?></p>
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
					<a data-ajax="false" href="?action=details&predicate=<?= $item->pred3 ?>&author=<?= $item->teacher ?>">			
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<?= _("Course name") ?>: <b><?= $item->title ?></b>
							</div>
							<div class="ui-block-b">
								<?= _("Teacher email") ?>: <b><?= $item->teacher ?></b>
								<div data-role="controlgroup" data-type="horizontal" style="float: right;">
									<?= _("Status") ?>: <b><?= _($item->accepted) ?></b>
								</div>
							</div>
						</div>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
<!-- END Page MyPublicationView-->