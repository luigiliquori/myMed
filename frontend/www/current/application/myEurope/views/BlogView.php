<div data-role="page" id="Blog">

	<? print_header_bar(false, true); ?>
	
	<div data-role="content" style="text-align:center;">
	<? print_notification($this->success.$this->error); ?>
		
	
		<br><br>
		<div id="phases_ul" >
		<ul data-role="listview" data-inset="true" data-filter="true">
			<li data-role="list-divider"><?= _('Phases du projet') ?></li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Ideas" ><?= _('Ideas') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Partner_Search" ><?= _('Partners search') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Application" ><?= _('Application') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Project_implementation" ><?= _('Project implementation') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Monitoring_and_evaluation" ><?= _('Monitoring and evaluation') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Communication" ><?= _('Communication') ?></a>
			</li>
			
			
			<li data-role="list-divider"><?= _('Thèmes de projet') ?></li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Education,_culture_and_sport" ><?= ('Education, culture and sport') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Work_and_training" ><?= ('Work and training') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Enterprises,_Research_and_Innovation" ><?= ('Enterprises, Research and Innovation') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Environment,_Energies_and_Risk" ><?= ('Environment, Energies and Risk') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Transport,_Facilities_and_Zoning" ><?= ('Transport, Facilities and Zoning') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Health_and_Consumer_Protection" ><?= ('Health and Consumer Protection') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Social_Affairs" ><?= ('Social Affairs') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Agriculture" ><?= ('Agriculture') ?></a>
			</li>
			<li>
				<a data-ajax="false" href="?action=Blog&method=Search&id=Fishing" ><?= ('Fishing') ?></a>
			</li>
		</ul>
		</div>
		
	</div>
</div>