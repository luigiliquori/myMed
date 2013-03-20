<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<?php
class Categories {
	
 	static $areas;
 	static $categories;
 	static $localities;
 	static $organizations;
 	static $roles;
 	
}

Categories::$areas = array(
	"Engineering, Mathematics & Computer Science" 	=> array("Telecommunication Engineering", "Electronic Engineering", "Mathematics", "Computer Engineering", "Mechanical Engineering", "Automatic Engineering", "Material Sciences", "Civil Engineering", "Architecture"),
	"Law, Economics and political science"			=> array("Economy", "Managerial Economics", "Law", "Management", "International Relations", "Political Sciences", "Public Service Sciences"),
	"Literature and philosophy" 					=> array("Letters and Literature", "Philosophy", "Theology"),
	"Foreign languages and literature" 				=> array("Foreign languages and literature"),
	"Education and Teacher training" 				=> array("Education and Teacher training"),
	"Social and Human Sciences" 					=> array("Psycology", "Sociology", "History", "Demography", "Ethnography"),
	"Information and Communication Sciences" 		=> array("Information & Communication", "Documentary resources and Data Bases", "Journalism"),
	"Art & Design" 									=> array("Technic of Image and Sound", "Music", "Plastic Arts"),
	"Health Sciences"								=> array("Pharmacy", "Veternary medicine", "Medicine and surgery", "Sport Sciences", "Health and Safety", "Odontology"),
	"Life Sciences and Earth" 						=> array("Geography", "Physics", "Chemistry", "Agricultural and food sciences", "Biology", "Ecosciences", "Geology")
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
