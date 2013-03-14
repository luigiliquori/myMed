<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 */
function print_header_bar($print_back_button = false, $print_logout_button = false, $idHelpPopup = "defaultHelpPopup") {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	if($print_back_button) {
		echo '<a href="#" data-rel="back" data-icon="arrow-l">' . translate('back') . '</a>';
	};
	echo '<h1>' . APPLICATION_NAME . ' <i>' . _('Social Network') .'</i></h1>';
	if($print_logout_button) {
		echo '<a href="?action=logout" 
				data-inline="true" 
				rel="external" 
				data-role="button" 
				data-theme="r" 
				data-icon="signout" 
				data-iconpos="notext">' . translate('Logout') . '</a>';
	}
	
	// Print help popup button
	if($idHelpPopup) {
		echo '<a href="#' . $idHelpPopup
		. '" id="openHelp" data-icon="question-sign"
			    class="ui-btn-right" data-theme="e" data-rel="popup">'
				. _('Help') . '</a>';
	}
	
	echo '</div>';
}

?>
