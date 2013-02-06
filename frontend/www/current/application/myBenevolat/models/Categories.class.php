<?php
/** 
 * Heer some key=>value fields useful in the application
 * are specified.
 * @author sspoto
 *
 */
class Categories {
	
 	static $roles;
 	static $profession;
}

/* MyBenevolat roles */
Categories::$roles = array(
	"Volunteer" => _("Volunteer"),
	"Association" => _("Association")	
);

/* MyBenevolat situation professionelle*/
Categories::$profession = array(
		"active" => _("Active"),
		"unemployed" => _("Unemployed"),
		"retired" => _("Retired"),
		"student" => _("Student")
);


?>
