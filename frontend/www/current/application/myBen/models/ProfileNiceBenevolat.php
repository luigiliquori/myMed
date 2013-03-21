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

/** Dummy singleton extended profile for nice benevolat */
class ProfileNiceBenevolat extends ProfileAssociation {
	
	/** Hardcoded user id for Nice benevolat */
	static public $USERID = "MYMED_nice-benevolat@yopmail.com"; 
	
	/** @var $instance ProfileNiceBenevolat */
	static private $instance;
	
	/** Singleton */
	static public function getInstance(){
		if (ProfileNiceBenevolat::$instance == null) {
			ProfileNiceBenevolat::$instance = new ProfileNiceBenevolat();
			ProfileNiceBenevolat::$instance->userID = ProfileNiceBenevolat::$USERID;
		}
		return ProfileNiceBenevolat::$instance;
	}
}