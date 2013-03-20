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
 * Print the footer bar
 * @param $activeTab, the current active view/tab
 * @param $tabs, the list of tabs
 */
function print_footer_bar($activeTab, $tabs) {
	echo '<div data-role="footer" data-theme="d" data-position="fixed" class="iostab">';
	echo '<div data-role="navbar">';
	echo '<ul>';
	foreach ($tabs as $tab) {
		echo '<li><a href="' . $tab[0] . '" data-icon="' . $tab[2] . '" data-ajax="false"';
		if ($activeTab == $tab[0]) {
			echo 'data-theme="b"';
		}
		echo '>' . translate($tab[1]) . '</a></li>';
	}
	echo'</ul></div></div>';
}

function print_footer_bar_login($activeTab) {
	print_footer_bar($activeTab, array(
	array("#login", "Sign in", "signin"),
	array("#register", "Create an account", "th-list"),
	array("#about", "About", "info-sign")
	));
}

function print_footer_bar_main($activeTab) {
	print_footer_bar($activeTab, array(
	array("?action=main", "Publish/subscribe", "user"),
	array("?action=analytics", "Stats 2", "user"),
	array("?action=stat", "Stats 3", "user")
	));
}

?>
