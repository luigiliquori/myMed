<div data-role="page" id="Blog" data-ajax="false">
	
 <? $title = _("Blog");
	print_header_bar("?action=main", false, $title); ?>
	
	<div data-role="content" >
	
		<? include_once 'notifications.php'; ?>
			
		<ul data-role="listview" data-inset="true" data-theme="d">
			<li data-role="list-divider"><?= _('Phases of the project') ?></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Ideas"><?= _('Ideas') ?></a></li>	    
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Partners search"><?= _('Partners search') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Application"><?= _('Application') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Project implementation"><?= _('Project implementation') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Monitoring and evaluation"><?= _('Monitoring and evaluation') ?></a></li> 
    		<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Communication"><?= _('Communication') ?></a></li> 
    		
    		<li data-role="list-divider"><?= _('Project themes') ?></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Education, culture and sport"><?= _('Education, culture and sport') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Work and training')"><?= _('Work and training') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Enterprises, Research and Innovation"><?= _('Enterprises, Research and Innovation') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Transport, Facilities and Zoning"><?= _('Transport, Facilities and Zoning') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Health and Consumer Protection"><?= _('Health and Consumer Protection') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Social Affairs"><?= _('Social Affairs') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Agriculture"><?= _('Agriculture') ?></a></li>
			<li data-icon="arrow-r"><a data-ajax="false" href="?action=blog&method=Search&cathegory=Fishing"><?= _('Fishing') ?></a></li>
		</ul>
		
		
	</div>
</div>