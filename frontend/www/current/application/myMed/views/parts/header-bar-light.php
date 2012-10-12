<?

function tab_bar_main($activeTab, $opts=1) {
	tabs_default($activeTab, array(
			array("?action=main", "Applications", "tags"),
			array("?action=profile", "Profil", "user"),
			array("?action=store", "Store", "shopping-cart")
	), $opts);
}

 
?>
