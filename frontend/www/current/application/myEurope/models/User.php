<?php

require_once('Entry.php');

class User extends Entry{


	public $details; // all user data
	
	public function __construct(
			$id = null,
			$data = null,
			$metadata = null,
			$index = null) {
		parent::__construct("users", $id, $data, $metadata, $index);
	}
	
}




?>