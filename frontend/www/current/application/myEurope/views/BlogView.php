<div data-role="page" id="Blog" data-ajax="false">
	
		<? print_header_bar(true, false); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
			
		<ul data-role="listview" data-inset="true" data-theme="d">
			<li data-role="list-divider"><?= _('Phases du projet') ?></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Ideas') ?>"><?= _('Ideas') ?></a></li>	    
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Partners_search') ?>"><?= _('Partners search') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Application') ?>"><?= _('Application') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Project_implementation') ?>"><?= _('Project implementation') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Monitoring_and_evaluation') ?>"><?= _('Monitoring and evaluation') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Communication') ?>"><?= _('Communication') ?></a></li> 
    		
    		<li data-role="list-divider"><?= _('Thèmes de projet') ?></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= ('Education,_culture_and_sport') ?>"><?= ('Education, culture and sport') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Work_and_training') ?>"><?= ('Work and training') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Enterprises,_Research_and_Innovation') ?>"><?= ('Enterprises, Research and Innovation') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Transport,_Facilities_and_Zoning') ?>"><?= ('Transport, Facilities and Zoning') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Health_and_Consumer_Protection') ?>"><?= ('Health and Consumer Protection') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Social_Affairs') ?>"><?= ('Social Affairs') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Agriculture') ?>"><?= ('Agriculture') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=<?= _('Fishing') ?>"><?= ('Fishing') ?></a></li>
		</ul>
		
		
	</div>
</div>