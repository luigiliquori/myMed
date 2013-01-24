<?php
class Categories {
	
 	static $areas;
 	static $categories;
 	static $localities;
 	static $organizations;
 	static $roles;
 	
}

Categories::$areas = array(
	"Aerospace"			=> _("Aerospace"),
	"Biomeccania"		=> _("Biomeccania"),
	"Cinema" 			=> _("Cinema"),
	"Civil" 			=> _("Civil"),
	"Data processing" 	=> _("Data processing"),
	"Electricity" 		=> _("Electricity"),
	"Electronics" 		=> _("Electronics"),
	"Energy" 			=> _("Energy"),
	"Environment"		=> _("Environment"),
	"Management" 		=> _("Management"),
	"Material" 			=> _("Material"),
	"Mathematics" 		=> _("Mathematics"),
	"Mechanics" 		=> _("Mechanics"),
	"Motor vehicle"		=> _("Motor vehicle"),
	"Physics" 			=> _("Physics"),
	"Telecommunications" => _("Telecommunications")
);

Categories::$categories = array(
	"Internship" 	=> _("Internship"),
	"Job" 			=> _("Job"),
	"Lecture note"		=> _("Lecture note"),
	"PHD Thesis"			=> _("PHD Thesis")
);

Categories::$localities = array(
	"Aix"   		=> _("Aix"),
	"Alessandria"   => _("Alessandria"),
	"Marseille"   	=> _("Marseille"),
	"Nice"   		=> _("Nice"),
	"Novara"   		=> _("Novara"),
	"Toulon"   		=> _("Toulon"),
	"Turin"   		=> _("Turin"),
	"Vercelli"   	=> _("Vercelli")
);

Categories::$roles = array(
	"role1"       => _("Description of role 1"),
	"role2"       => _("Description of role 2"),
	"role3"       => _("Description of role 3"),
);

Categories::$organizations = array(
	"Amu"     	=> _("Amu"),
	"Polito"	=> _("Polito"),
	"Unice"     => _("Unice"),
	"Unipo"     => _("Unipo"),
	"Unitln"    => _("Unitln"),
	"Unito"     => _("Unito"),
);

?>
