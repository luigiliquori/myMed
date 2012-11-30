<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 */
function print_header_bar($print_back_button = false, $idHelpPopup = "defaultHelpPopup") {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	
	if($print_back_button) {
		echo '<a data-rel="back" data-icon="arrow-left">' . _("Back") . '</a>';
	} else {
		include 'social.php';
	}
	
	echo '<h1>' . APPLICATION_NAME . '</h1>';
	echo '<a href="#" id="openHelp" onclick="$( \'#' . $idHelpPopup . '\' ).popup( \'open\' )" data-icon="question-sign" class="ui-btn-right" data-theme="e">' . _('Help') . '</a>';
	
	echo '</div>';
}

?>

