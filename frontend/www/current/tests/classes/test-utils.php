<?php

// Evaluate and compare two PHP expressions
function assertEqual($a, $b) {
	if ($a != $b) {
		throw new Exception("Assert equal failed: $a != $b");
	}
}

// compare fields of two objects
function assertObjectsEqual($a, $b) {
	
	// Get object values 
	$attrA = get_object_vars($a);
	$attrB = get_object_vars($b);
	
	// Get the keys
	$keysA = array_keys($attrA);
	$keysB = array_keys($attrA);
	
	// Compare the keys
	$keyDiff = array_diff($keysA, $keysB);
	if (sizeof($keyDiff) > 0) {
		throw new Exception("Attributes of A != Attributes of B : " . implode(", ", $keyDiff)); 
	}
	$keyDiff = array_diff($keysB, $keysA);
	if (sizeof($keyDiff) > 0) {
		throw new Exception("Attributes of A != Attributes of B : " . implode(", ", $keyDiff));
	}
	
	// Compare the values
	foreach($keysA as  $key) {
		if ($a->$key != $b->$key) {
			$valA = $a->$key;
			$valB = $b->$key;
			throw new Exception("\$a->$key != \$b->$key: '$valA' != '$valB'");
		}
	}
}


?>