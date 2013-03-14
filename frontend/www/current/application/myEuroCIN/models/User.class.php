<?php

require_once('Entry.class.php');

/**
 * User
 */
class User extends Entry {

	public $details; // all user data
	
	public function __construct(
			$id = null,
			$data = null,
			$metadata = null,
			$index = array()) {
		parent::__construct("users", $id, $data, $metadata, $index);
	}
	
}

?>