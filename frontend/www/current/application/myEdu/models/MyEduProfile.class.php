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

require_once('Entry.class.php');

/**
 * A user profile in MyEdu
 */
class MyEduProfile extends Entry {

	// Members
	public $users; 			
	// Partnerships owned or joined
	public $partnerships; 	
	// All profile data
	public $details; 
	
	
	/** 
	 * Constructor 
	 */
	public function __construct(
			$id = null,
			$data = null,
			$metadata = null,
			$index = array()) {
		parent::__construct("profiles", $id, $data, $metadata, $index);
	}

	
	/**
	 * ParseProfile
	 */
	public function parseProfile(){
		
		$this->users = array();
		$this->partnerships = array();
		
		foreach ($this->details as $k => $v){
			if (strpos($k, "user_") === 0){
				unset($this->details[$k]); // some cleaning for debug
				$this->users[] = $v;
			} else if(strpos($k, "part_") === 0){
				unset($this->details[$k]); // some cleaning for debug
				$this->partnerships[] = $v;
			}
		}
		sort($this->users);
	}
		
}
?>