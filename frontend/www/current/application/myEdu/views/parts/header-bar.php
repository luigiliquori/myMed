<?php 
/**
 * Print the header bar, this version is different from that in the template
 * 
 * @param boolean $print_back_button, add the back button to the header bar
 * @param string $idHelpPopup the id of the help popup in the rendered view  
 * @param string $title, $print_logout_button = false
 * @param boolean $print_logout_button, add the logout button to the header bar
 */
function print_header_bar($print_back_button = false, $idHelpPopup = "defaultHelpPopup", $title, $print_logout_button = false) {
	
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	
	// Print logout button or print
	if ($print_logout_button) {
		// Print logout button
		echo '<a href="?action=logout"
		data-inline="true"
		rel="external"
		data-role="button"
		data-theme="r"
		data-icon="off"
		data-iconpos="notext">' . _('Logout') . '</a>';
	
	} elseif ($print_back_button) {
		// Print back button 
		echo '<a href="#" data-rel="back" data-icon="arrow-l">' . _('back') . '</a>';
	};
	
	// Print application title
	$title = empty($title) ? APPLICATION_NAME : $title;
	echo '<h1>' .$title. '</h1>';
	
	// Print help popup button
	if($idHelpPopup) {
		echo '<a href="#' . $idHelpPopup . '" id="openHelp" data-icon="question-sign" class="ui-btn-right" data-theme="e" data-rel="popup">' . _('Help') . '</a>';
	}
	
	// Print social buttons
	// include 'social.php';
	
	echo '</div>';
}

?>
