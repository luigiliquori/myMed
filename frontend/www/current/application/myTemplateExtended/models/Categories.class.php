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
	
	static $categories;
 	static $areas;
 	static $organizations;
 	static $roles;
 	
}

Categories::$categories = array(
		"Course" => _("Course"),
		"Other"  => _("Other"),
);

Categories::$areas = array(
	"Area1"	=> _("Area 1"),
	"Area2"	=> _("Area 2"),
	"Area3" => _("Area 3")
);

Categories::$roles = array(
	"Role_1" => _("Role 1"),
	"Role_2" => _("Role 2")
	
);

Categories::$organizations = array(
	"Organization1" => _("Organization 1"),
	"Organization2"	=> _("Organization 2"),
	"Organization3" => _("Organization 3")
	
);

?>
