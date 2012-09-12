<?php
class Categories {
	
 	static $themes;
 	static $roles;
 	static $calls;
 	static $places_f;
 	static $places_i;
 	static $places_o;
 	
}

Categories::$themes = array(
		"education"       => _("Education, culture and sport"),
		"travail"         => _("Work and training"),
		"entreprise"      => _("Enterprises, Research and Innovation"),
		"environnement"   => _("Environment, Energies and Risk"),
		"recherche"       => _("Transport, Facilities and Zoning"),
		"santé"           => _("Health and Consumer Protection"),
		"social"          => _("Social Affairs"),
		"agriculture"     => _("Agriculture"),
		"peche"           => _("Fishing")
);

Categories::$roles = array(
		"assoc.."         => _("Association - Coopérative - Mutuelle"),
		"entr.."          => _("Entreprise privée"),
		"chamb.."         => _("Chambre consulaire - Groupement professionnel"),
		"univ.."          => _("Université - Recherche"),
		"commune"         => _("Commune, intercommunalité - établissement public communal ou intercommunal"),
		"departement"     => _("Département - établissement public départemental"),
		"region"          => _("Région - établissement public régional"),
		"etat"            => _("Service de l’Etat - établissement public de l’Etat"),
		"autre et.public" => _("Autre établissement ou groupement public"),
		"autre"           => _("Autre")
);

Categories::$calls = array(
		"alcotra.."       => _("Alcotra"),
		"ETC...."         => _(""),

);

Categories::$places_f = array(
		"Alpes Maritime",
		"ETC...",
		"vrvgr",
);
Categories::$places_i = array(
);
Categories::$places_o = array(
);

?>
