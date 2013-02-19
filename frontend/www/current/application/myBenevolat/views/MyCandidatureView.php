<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="mycandidatureesview">


	<!-- Page header bar -->
	<? $title = _("My candidatures");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("<<<<< Little help >>>>>") ?> ?</h3>
			<p><?= _("Here is the space summarizing all of your candidatures.")?></p>
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
					<a data-ajax="false" href="?action=details&id=<?= $item->idAnnonce ?>">			
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<?= _("Publication name") ?>: <b><?= $item->title ?></b>
							</div>							
							<div class="ui-block-b">
								<div style="float: right;">
									<?= _("Status") ?>: 
									<?  $status = "";
										if($item->accepted=="waiting")
											$status = _("Waiting the administrator validation");
										else $status = _("Has been validated by an administrator");
										echo "<b>".$status."</b>" ?>
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