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
		"Tradizioni" => _("Tradizioni"),
		"Enogastronomia" => _("Enogastronomia"),
		"Benessere" => _("Benessere"),
		"Storia" => _("Storia"),
		"Religione" => _("Religione"),
		"Escursioni_Sport" => _("Escursioni / Sport"),
);




