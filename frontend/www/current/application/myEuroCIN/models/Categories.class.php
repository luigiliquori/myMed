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
		"Alessandria" => _("Alessandria"),
		"Asti"	=> _("Asti"),
		"Cuneo" => _("Cuneo"),
		"Francia" => _("Francia"),
		"Genova" => _("Genova"),
		"Imperia" => _("Imperia"),
		"Savona" => _("Savona")
);


Categories::$languages = array(
		"italiano"	=> _("Italian"),
		"inglese"	=> _("English"),
		"francese" => _("French")
);

Categories::$categories = array(
		"Arte_Cultura" => _("Arte / Cultura"),
		"Natura" => _("Natura"),
		"Tradizioni" => _("Enogastronomia"),
		"Enogastronomia" => _("Benessere"),
		"Benessere" => _("Storia"),
		"Storia" => _("Arte / Cultura"),
		"Religione" => _("Arte / Cultura"),
		"Escursioni_Sport" => _("Arte / Cultura"),
);




