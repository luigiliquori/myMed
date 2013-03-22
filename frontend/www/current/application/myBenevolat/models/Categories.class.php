<?php
/*
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
 */
?>
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
 	static $competences;
 	static $mobilite;
 	static $missions;
 	static $disponibilites;

}


/* MyBenevolat professional competences */
Categories::$competences = array(
	"accompagnement" 	=> _("Accompagnement (social professionnel)"),
	"accueil" 			=> _("Accueil / Orientation"),
	"administration" 	=> _("Administration - Direction - Management"),
	"soutien_scolaire" 	=> _("Alphabétisation - Formation - Enseignement - Soutien scolaire"),
	"animation" 		=> _("Animation"),
	"communication" 	=> _("Communication"),
	"comptabilite" 		=> _("Comptabilité gestion"),
	"collecte" 			=> _("Distribution Collecte"),
	"ecoute" 			=> _("Ecoute"),
	"informatique" 		=> _("Informatique, Internet"),
	"juridique" 		=> _("Juridique"),
	"logistique" 		=> _("Logistique - Conseil - Secrétariat - Administration courante"),
	"travaux" 			=> _("Travaux (manuels et techniques)"),
	"visite_domicile"	=> _("Visite domicile"),
	"visite_hopital"	=> _("Visite hôpital"),
	"visite_prison" 	=> _("Visite prison")
);

/* MyBenevolat mobilitie */
Categories::$mobilite = array(
	"undef"				=> _("-- Non défini --"),
	"plaine_coteaux"	=> _("Plaine et coteaux "),
	"collines_nicoises" => _("Collines niçoises "),
	"trois_collines" 	=> _("Trois collines "),
	"rives_paillon" 	=> _("Rives du Paillon "),
	"est_littoral" 		=> _("Est littoral "),
	"nord_centre_ville" => _("Nord centre ville "),
	"coeur_ville" 		=> _("Coeur de ville "),
	"ouest_littoral" 	=> _("Ouest littoral")
);

/* MyBenevolat missions */
Categories::$missions = array(
	"ponctuel"	=> _("Ponctuel"),
	"regulier" 	=> _("Régulier"),
	"urgence" 	=> _("Urgence")
);

Categories::$disponibilites = array(
	"semaine_journee" 	=> _("Semaine, en journée"),
	"semaine_soir"		=> _("Semaine, le soir"),
	"we_journee" 		=> _("WE, en journée"),
	"we_soir" 			=> _("WE, le soir")
);

?>
