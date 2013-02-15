<?php
/** 
 * Heer some key=>value fields useful in the application
 * are specified.
 * @author sspoto
 *
 */
class Categories {
	
	static $categories;
 	static $areas;
 	static $organizations;
 	static $roles;
 	
}

Categories::$categories = array(
		"Course" => _("Course"),
		"Other"  => _("Other"),
);

Categories::$areas = array(
	"Area1"	=> _("Area1"),
	"Area2"	=> _("Area2"),
	"Area3" => _("Area3")
);

Categories::$roles = array(
	"Role_1" => _("Role_1"),
	"Role_2" => _("Role_2")
	
);

Categories::$organizations = array(
	"Organization1" => _("Organization1"),
	"Organization2"	=> _("Organization2"),
	"Organization3" => _("Organization3")
	
);

?>
