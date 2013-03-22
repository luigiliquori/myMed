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


class Contact {
	
	public /*String*/ $type;
	
	public /*String*/ $nickname;
	
	public /*String*/ $firstname;
	
	public /*String*/ $lastname;
	
	public /*String*/ $address;
	
	public /*String*/ $email;
	
	public /*String*/ $phone;
	
	
	
	public function __construct($type, $nickname, $firstname, $lastname, $address, $email, $phone){
		
		/***************************************
		 * TYPE
		 * Can be : caregiver, buddy, doctor, emergency
		 */
		$this->type 		= $type;
		
		/***************************************
		 * NICKNAME
		 */
		if (empty($nickname))
			$this->nickname		= $firstname + " " + $lastname;
		else
			$this->nickname		= $nickname;
		
		/***************************************
		 * FIRSTNAME
		 */
		$this->firstname	= $firstname;
		
		/***************************************
		 * LASTNAME
		 */
		$this->lastname		= $lastname;
		
		/***************************************
		 * ADDRESS
		 */
		if(empty($address)){
			if ($type == "caregiver" OR $type == "buddy")
				throw new Exception("Address must not be empty");
			else
				$this->address	= "-";
		}
		else
			$this->address	=	$address;
		
		/***************************************
		 * EMAIL
		 */
		if(empty($email)){
			if ($type == "caregiver" OR $type == "buddy")
				throw new Exception("E-mail must not be empty");
			else
				$this->email	= "-";
		}
		else
			$this->email	= $email;
		
		/***************************************
		 * PHONE
		 */
		if (empty($phone))
			throw new Exception("Phone number must not be empty");
		else
			$this->phone		= $phone;
		
	}
	
}