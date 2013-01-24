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