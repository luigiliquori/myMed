<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
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