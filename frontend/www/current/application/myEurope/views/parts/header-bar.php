<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 * 				
 */

function print_header_bar($print_back_button = false, $idHelpPopup = "defaultHelpPopup", $title, $print_logout_button = false) {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	
	// Pring logout button
	if($print_logout_button && !($_SESSION['user']->is_guest)) {
		echo '<a href="?action=logout" class="ui-btn-left" data-role="button" rel="external" data-icon="off" data-iconpos="notext" data-theme="r">Sign out</a>';
	}
		  
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

