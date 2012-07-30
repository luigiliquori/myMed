<?php
//require_once '../../../lib/dasp/request/PublishRequest.class.php';
//require_once '../../../lib/dasp/beans/DataBean.php';
//require_once '../../../lib/dasp/beans/OntologyBean.php';

// Constant for permission level
define('PENDING', 0);
define('NORMAL'	, 1);
define('ADMIN'	, 2);

class ExtendedProfile
{

	/*
	 * Attributes
	 */

	/**
	 * The ID of the myMed user to whom this ExtendedProfile is linked.
	 */
	public /*String*/ $user;
	
	public /*String*/ $role;
	public /*enum*/   $permission;
	public /*String*/ $name;
	public /*String*/ $activity;
	public /*String*/ $address;
	public /*String*/ $phone;
	public /*String*/ $email;
	public /*String*/ $siret;
	
	
	
	public function __construct( $obj ){
		
		$this->user = $obj->user;
		$this->role = $obj->role;
		$this->permission = $obj->permission;
		$this->name = $obj->name;
		$this->activity = $obj->activity;
		$this->address = $obj->address;
		$this->phone = $obj->phone;
		$this->email = $obj->email;
		$this->siret = $obj->siret;

	}


	
}