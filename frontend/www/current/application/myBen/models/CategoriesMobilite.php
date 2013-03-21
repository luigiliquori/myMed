<?php
class CategoriesMobilite {
 	static $values = array();
 	static $values_no_undef;
}
CategoriesMobilite::$values["undef" ]=_("-- Non défini --");
CategoriesMobilite::$values["plaine_coteaux" ]=_("Plaine et coteaux ");
CategoriesMobilite::$values["collines_nicoises" ]=_("Collines niçoises ");
CategoriesMobilite::$values["trois_collines" ]=_("Trois collines ");
CategoriesMobilite::$values["rives_paillon" ]=_("Rives du Paillon ");
CategoriesMobilite::$values["est_littoral" ]=_("Est littoral ");
CategoriesMobilite::$values["nord_centre_ville" ]=_("Nord centre ville ");
CategoriesMobilite::$values["coeur_ville" ]=_("Coeur de ville ");
CategoriesMobilite::$values["ouest_littoral" ]=_("Ouest littoral");

// Static block
CategoriesMobilite::$values_no_undef = CategoriesMobilite::$values;
unset(CategoriesMobilite::$values_no_undef['undef']);

?>
