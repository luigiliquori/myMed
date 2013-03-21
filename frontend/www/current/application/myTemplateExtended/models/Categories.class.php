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
	"Area1"	=> _("Area 1"),
	"Area2"	=> _("Area 2"),
	"Area3" => _("Area 3")
);

Categories::$roles = array(
	"Role_1" => _("Role 1"),
	"Role_2" => _("Role 2")
	
);

Categories::$organizations = array(
	"Organization1" => _("Organization 1"),
	"Organization2"	=> _("Organization 2"),
	"Organization3" => _("Organization 3")
	
);

?>
