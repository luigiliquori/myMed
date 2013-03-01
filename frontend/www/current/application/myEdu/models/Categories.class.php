<?php
class Categories {
	
 	static $areas;
 	static $categories;
 	static $localities;
 	static $organizations;
 	static $roles;
 	
}

Categories::$areas = array(
	"Engineering, Mathematics & Computer Science" 	=> ["Telecommunication Engineering", "Electronic Engineering", "Mathematics", "Computer Engineering", "Mechanical Engineering", "Automatic Engineering", "Material Sciences", "Civil Engineering", "Architecture"],
	"Law, Economics and political science"			=> ["Economy", "Managerial Economics", "Law", "Management", "International Relations", "Political Sciences", "Public Service Sciences"],
	"Literature and philosophy" 					=> ["Letters and Literature", "Philosophy", "Theology"],
	"Foreign languages and literature" 				=> ["Foreign languages and literature"],
	"Education and Teacher training" 				=> ["Education and Teacher training"],
	"Social and Human Sciences" 					=> ["Psycology", "Sociology", "History", "Demography", "Ethnography"],
	"Information and Communication Sciences" 		=> ["Information & Communication", "Documentary resources and Data Bases", "Journalism"],
	"Art & Design" 									=> ["Technic of Image and Sound", "Music", "Plastic Arts"],
	"Health Sciences"								=> ["Pharmacy", "Veternary medicine", "Medicine and surgery", "Sport Sciences", "Health and Safety", "Odontology"],
	"Life Sciences and Earth" 						=> ["Geography", "Physics", "Chemistry", "Agricultural and food sciences", "Biology", "Ecosciences", "Geology"]
);

Categories::$categories = array(
	"Internship" 	=> _("Internship"),
	"Job" 			=> _("Job"),
	"Lecture note"	=> _("Lecture note"),
	"Course"		=> _("Course"),
	"PHD Thesis"	=> _("PHD Thesis")
);

Categories::$localities = array(
	"Riviera"   	=> _("French Riviera"),
	"Rhône_Alpes"   => _("Rhône-Alpes"),
	"Ligury"   		=> _("Ligury"),
	"Piedmont"   	=> _("Piedmont"),
	"Aosta_Valley" 	=> _("Aosta Valley"),
	"Monaco"   		=> _("Monaco")
);


Categories::$roles = array(
	"student"   => _("Student"),
	"professor" => _("Professor"),
	"company"   => _("Company")
);

Categories::$organizations = array(
	"Aix-Marseille"	=> _("Aix-Marseille Univ."),
	"UNS" 			=> _("Nice Sophia-Antipolis Univ."),
	"Toulon"		=> _("South Toulon-Var Univ."),
	"Sup. Lyon"    	=> _("Ecole Normale Sup. Lyon"),
	"Lyon I II III" => _("Lyon I, II & III Univ."),
	"Grenoble"    	=> _("Grenoble Univ."),
	"Torino"     	=> _("Politecnico di Torino"),
	"Turin"     	=> _("Turin Univ."),
	"Genoa"     	=> _("Genoa Univ."),
	"Piedmont"     	=> _("Oriental Piedmont Univ."),
	"Aosta Valley"  => _("Aosta Valley Univ."),
	"Enterprise"   	=> _("Enterprise")
);

?>
