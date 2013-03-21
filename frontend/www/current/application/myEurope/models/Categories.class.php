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
class Categories {
	
 	static $themes;
 	static $roles;
 	static $calls;
 	static $places_fr;
 	static $places_it;
 	static $places_ot;
 	static $phases;
 	static $keywords;
 	static $territorytype;
 	
}

Categories::$territorytype = array(
	"urban"       => _("urban"),
	"rural"         => _("rural"),
	"mountain"      => _("mountain"),
	"maritime"   => _("maritime"),
);

Categories::$themes = array(
	
	"education"       => _("Education, culture and sport"),
	"travail"         => _("Work and training"),
	"entreprise"      => _("Enterprises, Research and Innovation"),
	"environnement"   => _("Environment, Energies and Risk"),
	"infrastructure"  => _("Transport, Facilities and Zoning"),
	"santé"           => _("Health and Consumer Protection"),
	"social"          => _("Social Affairs"),
	"agriculture"     => _("Agriculture"),
	"peche"           => _("Fishing")
);

Categories::$roles = array(
	"assoc"           => _("Association - Cooperative - Mutual"),
	"entr"            => _("Private enterprise"),
	"chamb"           => _("Consular Chamber - Professional Association"),
	"univ"            => _("University - Research"),
	"commune"         => _("City, inter communality - communal or intercommunal public institution"),
	"departement"     => _("Department - departmental public institution"),
	"region"          => _("Region - regional public institution"),
	"etat"            => _("Department of State - State public institution"),
	"autreEtPublic"   => _("Another facility or public group"),
	"autre"           => _("Other")
);

Categories::$calls = array(
	"libre"           => _("Proposition libre"),
	"alcotra"         => _("Alcotra"),
	"med"             => _("MED"),
	"esp"             => _("Espace Alpin"),
	"ievp"            => _("IEVP CT MED"),
	"interreg"        => _("Interreg IV C"),
	"mar"             => _("Italie-France Maritime"),

);

Categories::$places_fr = array(
	_("Ain"),
	_("Alpes-Maritimes"),
	_("Alpes de Haute-Provence"),
	_("Bouches-du-Rhône"),
	_("Drôme"),
	_("Hautes-Alpes"),
	_("Haute-Savoie"),
	_("Isère"),
	_("Rhône"),
	_("Savoie"),
	_("Var"),
	_("Vaucluse"),
	
		
);
Categories::$places_it = array(
	_("Alessandria"),
	_("Aosta"), 
	_("Asti"), 
	_("Biella"),
	_("Cuneo"), 
	_("Genova"), 
	_("Imperia"),
	_("Savona"),
	_("Torino"),
	_("Vercelli"),
		
);

Categories::$places_ot = array(
	_("Monaco"),
	_("Switzerland"),
	_("Corsica"),
);

Categories::$phases = array(
	_("Ideas"),
	_("Partners search"),
	_("Application"),
	_("Project implementation"),
	_("Monitoring and evaluation"),
	_("Communication"),
);

Categories::$keywords = array(
	"administratif",
	"administration",
	"air",
	"alimentation",
	"basse",
	"biodiversité",
	"bois",
	"camping",
	"carbone",
	"chances",
	"changement",
	"climat",
	"C02",
	"commerce",
	"commune",
	"communication",
	"compétitivité",
	"connexion",
	"coopération",
	"création",
	"culture",
	"distribution",
	"durable",
	"déchets",
	"développement",
	"eau",
	"économie",
	"écosystème",
	"éducation",
	"efficacité",
	"égalité",
	"émission",
	"emploi",
	"énergies",
	"énergétique",
	"entreprise",
	"environnement",
	"faible",
	"femmes",
	"ferroviaire",
	"formation",
	"forêt",
	"hommes",
	"inclusion",
	"information",
	"innovation",
	"institutionnel",
	"intelligent",
	"internationalisation",
	"intégration",
	"investissement",
	"juridique",
	"local",
	"logement",
	"lois",
	"medecine",
	"mobilité",
	"multimodal",
	"oeuvre",
	"online",
	"patrimoine",
	"PME",
	"pollution",
	"protection",
	"public",
	"pêche",
	"recherche",
	"renouvelables",
	"ressources",
	"risques",
	"rural",
	"réseaux",
	"santé",
	"service",
	"social",
	"technologie",
	"tension",
	"TIC",
	"transition",
	"transport",
	"urbain",
	"utilisation",
	"vert"
);

?>
