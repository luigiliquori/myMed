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
 	
}

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
	"assoc"           => _("Association - Coopérative - Mutuelle"),
	"entr"            => _("Entreprise privée"),
	"chamb"           => _("Chambre consulaire - Groupement professionnel"),
	"univ"            => _("Université - Recherche"),
	"commune"         => _("Commune, intercommunalité - établissement public communal ou intercommunal"),
	"departement"     => _("Département - établissement public départemental"),
	"region"          => _("Région - établissement public régional"),
	"etat"            => _("Service de l'Etat - établissement public de l'Etat"),
	"autreEtPublic"   => _("Autre établissement ou groupement public"),
	"autre"           => _("Autre")
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
	"water",
	"sky",
	"aa",
	"About",
	"Alcotra",
	"test"
);

?>
