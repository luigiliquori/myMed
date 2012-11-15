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
