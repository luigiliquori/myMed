<?php
class Categories {
	
 	static $areas;
 	static $categories;
 	static $localities;
 	static $organizations;
 	static $roles;
 	
}

Categories::$areas = array(
	"Aerospaziale"		=> _("Aéronautique"),
	"Ambientale"		=> _("Environnement"),
	"Autoveicolo"		=> _("Véhicules automobiles"),
	"Biomeccania"		=>  _("Biomeccania"),
	"Cinema" 			=> _("Cinèma"),
	"Civile" 			=> _("Civil"),
	"Elettrica" 		=> _("Èlectricité"),
	"Elettronica" 		=> _("Electronics"),
	"Energetica" 		=> _("Energie"),
	"Fisica" 			=> _("Physique"),
	"Gestionale" 		=> _("Gestion"),
	"Informatica" 		=> _("Informatique"),
	"Matematica" 		=> _("Mathématiques"),
	"Materiali" 		=> _("Matériaux"),
	"Meccanica" 		=> _("Mécanique"),
	"Telecomunicazioni" => _("Télécommunications")
);

Categories::$categories = array(
	"Stage" 	=> _("Stage"),
	"Job" 		=> _("Job"),
	"Tesi"		=> _("Thèse"),
	"Appunti" 	=> _("Remarques")
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

Categories::$organizations = array(
	"organization1"       => _("Description of organization 1"),
	"organization2"       => _("Description of organization 2"),
	"organization3"       => _("Description of organization 3"),
);

?>
