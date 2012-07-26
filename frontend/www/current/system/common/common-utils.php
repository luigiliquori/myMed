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

/** Build an URL with "<action>[:<method>]".
 *  For example :
 *  * url("profile:create") => "?action=profile&method=create"
 *  * url("profile", array("foo" => "bar")) => "?action=profile&foo=bar"
 */
function url($action, $args=array()) {
	$parts = explode(":", $action);
	$args["action"] = $parts[0];
	if (sizeof($parts) > 1) $args["method"] = $parts[1];
	return "?" . http_build_query($args);
}


?>