<?

function tab_bar_main($activeTab, $opts = 1) {
	
	// Set up second tab (sign in or profile)
	if($_SESSION['user']->is_guest )
		$tab = array("?action=login#login", "Sign in", "signin");
	else 
		$tab = array("?action=profile", "Profil", "user");
	
	tabs_default($activeTab, array(
			array("?action=main", "Applications", "tags"),
			$tab,
			array("?action=store", "Store", "shopping-cart")
	), $opts);
	//include 'social.php';
}

 
?>
