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
/** 
 * Heer some key=>value fields useful in the application
 * are specified.
 * @author sspoto
 *
 */
class Categories {
	
	static $localities;
	static $languages;
	static $categories;
 	
}

Categories::$localities = array(
		"Alessandria" => _("Alessandria"),
		"Asti"	=> _("Asti"),
		"Cuneo" => _("Cuneo"),
		"Francia" => _("Francia"),
		"Genova" => _("Genova"),
		"Imperia" => _("Imperia"),
		"Savona" => _("Savona")
);


Categories::$languages = array(
		"italiano"	=> _("Italian"),
		"inglese"	=> _("English"),
		"francese" => _("French")
);

Categories::$categories = array(
		"Arte_Cultura" => _("Arte / Cultura"),
		"Natura" => _("Natura"),
		"Tradizioni" => _("Tradizioni"),
		"Enogastronomia" => _("Enogastronomia"),
		"Benessere" => _("Benessere"),
		"Storia" => _("Storia"),
		"Religione" => _("Religione"),
		"Escursioni_Sport" => _("Escursioni / Sport"),
);




