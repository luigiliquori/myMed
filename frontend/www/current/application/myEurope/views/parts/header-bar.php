<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 */
function print_header_bar($print_back_button = false, $idHelpPopup = "defaultHelpPopup", $title) {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	
	if($print_back_button) {
		echo '<a data-rel="back" data-icon="arrow-left">' . _("Back") . '</a>';
	} else {
// 		include 'social.php';
	}
	$title=empty($title)?APPLICATION_NAME:$title;
	
	echo '<h1>' .$title. '</h1>';
	
	if($idHelpPopup) {
		echo '<a href="#' . $idHelpPopup . '" id="openHelp" data-icon="question-sign" class="ui-btn-right" data-theme="e" data-rel="popup">' . _('Help') . '</a>';
	}
	
	echo '</div>';
}

?>

