<!-- --------------------------------- -->
<!-- MyCandidatureView                 -->
<!-- Show the status of user's applies -->
<!-- --------------------------------- -->


<!-- page view View -->
<div data-role="page" id ="allvolunteersview">


	<!-- Page header bar -->
	<? $title = _("Volunteers list");
	   print_header_bar("?action=main", false, $title); ?>
	
		
	<!-- Page content -->
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
		
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Volunteers list capsule title") ?></h3>
			<p><?= _("Volunteers list capsule text")?></p>
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
 					<a href="?action=extendedProfile&method=show_user_profile&user=<?= $item->id ?> ">
		 				<fieldset class="ui-grid-a">
							<div class="ui-block-a">
								<?= _("Name") ?>: <b><?= $item->name ?></b>
							</div>
							<div class="ui-block-b">
								<div style="float: right;">	
									<?= $item->email ?>
								</div>
							</div>
						</fieldset>
					</a>
 				</li>
			<? endforeach ?>
		</ul>
			
	</div>
	<!-- END Page content -->
	
</div>
