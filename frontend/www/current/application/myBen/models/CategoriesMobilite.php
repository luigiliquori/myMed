<?php
class CategoriesMobilite {
 	static $values = array(
 		"undef" => "-- Non défini --",
 		"plaine_coteaux" => "Plaine et coteaux ",
		"collines_nicoises" => "Collines niçoises ",
		"trois_collines" => "Trois collines ",
		"rives_paillon" => "Rives du Paillon ",
		"est_littoral" => "Est littoral ",
		"nord_centre_ville" => "Nord centre ville ",
		"coeur_ville" => "Coeur de ville ",
		"ouest_littoral" => "Ouest littoral"
 	);
 	
 	static $values_no_undef;
}

// Static block
CategoriesMobilite::$values_no_undef = CategoriesMobilite::$values;
unset(CategoriesMobilite::$values_no_undef['undef']);

?>
