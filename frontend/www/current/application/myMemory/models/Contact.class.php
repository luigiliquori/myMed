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