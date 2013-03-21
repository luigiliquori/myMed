<?php
/*
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
 */
?>
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
	
	echo '<h2>' .$title. '</h2>';
	
	if($idHelpPopup) {
		echo '<a href="#' . $idHelpPopup . '" id="openHelp" data-icon="question-sign" class="ui-btn-right" data-theme="e" data-rel="popup">' . _('Help') . '</a>';
	}
	
	echo '</div>';
}

?>

