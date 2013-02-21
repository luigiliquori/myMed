<?php
/** 
 * Heer some key=>value fields useful in the application
 * are specified.
 * @author sspoto
 *
 */
class Categories {
	
	static $localities;
	static $languages;
	static $categories;
 	
}

Categories::$localities = array(
		"alessandria" => _("Alessandria"),
		"asti"	=> _("Asti"),
		"cuneo" => _("Cuneo"),
		"francia" => _("Francia"),
		"genova" => _("Genova"),
		"imperia" => _("Imperia"),
		"savona" => _("Savona")
);


Categories::$languages = array(
		"italian"	=> _("Italiano"),
		"english"	=> _("Inglese"),
		"france" => _("Francese")
);

Categories::$categories = array(
		"art" => _("Arte / Cultura"),
		"nature" => _("Natura"),
		"tradition" => _("Enogastronomia"),
		"enogastronomy" => _("Benessere"),
		"wellness" => _("Storia"),
		"story" => _("Arte / Cultura"),
		"religion" => _("Arte / Cultura"),
		"sport" => _("Arte / Cultura"),
);




