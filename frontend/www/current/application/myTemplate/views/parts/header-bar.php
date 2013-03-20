<!--
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
 -->
<?php 
/**
 * Print the header bar
 * @param boolean $print_back_button, add the back button to the header bar
 * @param boolean $print_logout_button, add the logout button to the header bar
 */
function print_header_bar($print_back_button = false, $print_logout_button = false) {
	echo '<div data-role="header" data-theme="b" data-position="fixed">';
	if($print_back_button) {
		echo '<a href="#" data-rel="back" data-icon="arrow-l">' . _('back') . '</a>';
	};
	echo '<h1>' . APPLICATION_NAME . '</h1>';
	if($print_logout_button) {
		echo '<a href="?action=logout" 
				data-inline="true" 
				rel="external" 
				data-role="button" 
				data-theme="r" 
				data-icon="signout" 
				data-iconpos="notext">' . _('Logout') . '</a>';
	}
	include 'social.php';
	echo '</div>';
}

?>
