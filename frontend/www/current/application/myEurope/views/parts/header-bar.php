<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 * 				
 */
function print_header_bar($print_back_button = false, $idHelpPopup = "defaultHelpPopup", $print_logout_button = false) {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	
	// Pring logout button
	if($print_logout_button ){
			echo '<a href="?action=logout"
			data-inline="true"
			rel="external"
			data-role="button"
			data-theme="r"
			data-icon="off"
			data-iconpos="notext">' . _('Logout') . '</a>';
	}
		
	if($print_back_button) {
		
		echo '<a data-rel="back" data-icon="arrow-left">' . _("Back") . '</a>';
	} else {
// 		include 'social.php';
	}
	
	echo '<h1>' . APPLICATION_NAME . '</h1>';
	
	if($idHelpPopup) {
		echo '<a href="#' . $idHelpPopup . '" id="openHelp" data-icon="question-sign" class="ui-btn-right" data-theme="e" data-rel="popup">' . _('Help') . '</a>';
	}
	
	echo '</div>';
}

?>

