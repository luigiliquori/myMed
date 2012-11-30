<div data-role="page" id="blog">

	<? print_header_bar(false, true); ?>

	<div data-role="content" style="text-align:center;">
	
		<? include_once 'notifications.php'; ?>
	
		<br><br>
		<br><br>
		<div id="phases_ul" >
		<ul data-role="listview" data-inset="true" data-filter="true">
			<li data-role="list-divider"><?= _('Phases du projet') ?></li>
			<? foreach (Categories::$phases as $k=>$v): ?>
			<li>
				<a data-ajax="false" href="?action=Blog&id=<?= $v ?>" ><?= $v ?></a>
			</li>
			<? endforeach; ?>
			
			<li data-role="list-divider"><?= _('Thèmes de projet') ?></li>
			<? foreach (Categories::$themes as $k=>$v): ?>
			<li>
				<a data-ajax="false" href="?action=Blog&id=<?= $v ?>" ><?= $v ?></a>
			</li>
			<? endforeach; ?>
		</ul>
		</div>
		
		
		
		<br>
		<div data-role="collapsible" data-collapsed="true" data-mini="true" data-content-theme="c">
			<h3>Beta tests</h3>
			<ul data-role="listview" >
				<li data-role="list-divider"></li>
				<li>
					<a href="?action=Blog&id=myEurope"  rel="external" class="mymed-huge-button"><?= _('Bugs et problèmes rencontrés') ?></a>
				</li>
				<li>
					<a href="?action=Blog&id=Ameliorations proposees"  rel="external" class="mymed-huge-button"><?= _('Améliorations proposées') ?></a>
				</li>
				<li>
					<a href="?action=Blog&id=Discussion libre"  rel="external" class="mymed-huge-button"><?= _('Discussion libre') ?></a>
				</li>
			</ul>
		</div>
		
	</div>
</div>