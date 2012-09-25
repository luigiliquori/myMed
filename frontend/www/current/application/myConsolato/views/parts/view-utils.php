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