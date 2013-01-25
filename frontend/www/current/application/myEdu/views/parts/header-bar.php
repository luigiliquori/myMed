<?php 
/**
 * Print the header bar
 * 
 * @param boolean $back_button print back button 
 * 			allowed values for back button:
 * 				false: no back button
 * 				"logout" print logout button
 * 				"a_custom_address" the back button points to a custom address
 * 
 * @param string $idHelpPopup the id of the help popup in the rendered view  
 * @param string $title, $print_logout_button = false
 */
function print_header_bar($back_button = false, 
						  $idHelpPopup = "defaultHelpPopup", 
						  $title,
						  $back_button_label = 'back') {
	
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	
	switch($back_button) {
		
		// No back button
		case false:
			break;
		
		// Standart back button ('back' or true works here)
		case 'back':
			echo '<a href="#" data-rel="back" data-icon="arrow-l" >'
				. _($back_button_label) . '</a>';
			break;
			
		// Print logout button
		case 'logout':
			if(!$_SESSION['user']->is_guest) {
				// If the user is logged print logout button ...
				echo '<a href="
							?action=logout"
							data-inline="true"
							rel="external"
							data-role="button"
							data-theme="r"
							data-icon="off"
							data-iconpos="notext"
							>' . _('Logout') . '</a>';
			} else {
				// ... otherwise back to the launchpad
				echo '<a href="/" data-icon="arrow-l" data-ajax="false">' 
					. _('myMed') . '</a>';
			}
			break;
		
		// Otherwise it is a custom link
		default:
			echo '<a href="' . $back_button . '" data-icon="arrow-l" data-ajax="false">'
			. _($back_button_label) . '</a>';
			break;
			
	}
	
	// Print application title
	$version_application_name = APPLICATION_NAME." v1.0 alpha";
	$title = empty($title) ? $version_application_name : $title;
	echo '<h1>' .$title. '</h1>';
	
	// Print help popup button
	if($idHelpPopup) {
		echo '<a href="#' . $idHelpPopup 
			. '" id="openHelp" data-icon="question-sign" 
			    class="ui-btn-right" data-theme="e" data-rel="popup">' 
			. _('Help') . '</a>';
	}
	
	// Print social buttons
	// include 'social.php';
	
	echo '</div>';
}

?>
