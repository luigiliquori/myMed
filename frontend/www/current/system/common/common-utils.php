<?

// ---------------------------------------------------------------------
// General purpose utils (controllers)
// ---------------------------------------------------------------------

/** Test whether a var is in the GET or POST arguments */
function in_request($varname) {
	return isset($_REQUEST[$varname]);
}

/** Return true if the value is true or "1" or 1 or "true" */
function is_true($value) {
	return (($value === true) || ($value === 1) || ($value === "1") || ($value === "true"));
} 

// ---------------------------------------------------------------------
// View utils (views)
// ---------------------------------------------------------------------

/** 
 *  Build an URL with "<action>[:<method>]".
 *  For example :
 *  * url("profile:create") => "?action=profile&method=create"
 *  * url("profile", array("foo" => "bar")) => "?action=profile&foo=bar"
 *  If action begins with "/", it is considered as absolute path 
 *  instead of action.
 *  If action == "<self>", all current GET parameters are copied and merged with supplied "$args".
 */
function url($action, $args=array()) {
	global $ACTION;
	
	// Real path
	if ($action[0] == '/') return $action;
		
	// Same action
	if ($action == "<self>") { 
		$args = array_merge($_GET, $args);
	} else {
		// Action:method
		$parts = explode(":", $action);
		$args["action"] = $parts[0];
		if (sizeof($parts) > 1) $args["method"] = $parts[1];
	}
	
	// Build url
	return "?" . http_build_query($args);
}


?>