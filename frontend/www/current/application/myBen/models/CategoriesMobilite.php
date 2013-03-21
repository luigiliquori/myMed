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
