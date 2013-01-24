<?php
class Categories {
	
 	static $areas;
 	static $categories;
 	static $localities;
 	static $roles;
 	
}

Categories::$areas = array(
	"area1"       => _("Description of area 1"),
	"area2"       => _("Description of area 2"),
	"area3"       => _("Description of area 3"),
);

Categories::$categories = array(
	"category1"       => _("Description of category 1"),
	"category2"       => _("Description of category 2"),
	"category3"       => _("Description of category 3"),
);

Categories::$localities = array(
	"locality1"   => _("Description of locality 1"),
	"locality2"   => _("Description of locality 2"),
	"locality3"   => _("Description of locality 3"),

);

Categories::$roles = array(
		"student"   => _("Student"),
		"professor" => _("Professor"),
		"company"   => _("Company"),
);

?>
