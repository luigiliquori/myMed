<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 */
function print_header_bar($print_back_button = false, $print_logout_button = false) {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	include 'social.php';
	echo '<h1>' . APPLICATION_NAME . '</h1>';
	echo '<a href="#"  onclick="$( \'#popupHelp\' ).popup( \'open\' )" data-icon="question-sign" class="ui-btn-right" data-theme="e">' . _('help') . '</a>';
	echo '</div>';
}

?>

