<?php 


/**
 *  Generates a navbar of tabs with appropriate transitions (left / right).
 *  $tabs : An array of "<tabId>" => "<Label>"
 *          Where tabId is the id of the page = The id of the div with data-role="page"
 *  $ActiveTab : Current active tab id 
 *  These tabs should be repeated in the header of each tabbed page
 */
function tabs($tabs, $activeTab) {
	
	$reverse = true;
	?> 	
	<div data-role="navbar"> 
	<ul>
	<? foreach ($tabs as $id => $label) { ?>
		<li>
			<a 
				href="#<?= $id ?>"    
				data-transition="none" 
				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
				<?= ($activeTab == $id) ? 'data-theme="e" ' : 'data-theme="d" ' ?> >
				<?= $label ?>
			</a>
		</li><? 
		
		if ($id == $activeTab) {
			$reverse = false;
		}
	}

	?> 
	</ul>
	</div> <?
 } 
 
?>