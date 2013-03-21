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
<?

/** 
 * Generates the crumb for a category, with hierachy to the root 
 * @param $cat InstitutionCategory 
 * 
 */
function category_crumb($cat) {
	
	// Root category ?
	if ($cat->id == "root") {
		// Ropot crumb => "instituion"
		return array(_("Institution") => url("institutionCategory"));
	} else {
		// Get parent crumb
		$crumb = category_crumb($cat->getParent());
		$crumb[$cat->title] =
			url(
					"institutionCategory:show", 
					array("id" => $cat->id));
		
		return $crumb;
	}
}


?>