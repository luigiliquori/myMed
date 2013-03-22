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