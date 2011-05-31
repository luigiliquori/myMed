<?php
require_once dirname(__FILE__).'/JSon.class.php';
class Profile extends JSon
{
	public /*int*/		$mymedID			= null;
	public /*string*/	$socialNetworkID	= null;
	public /*string*/	$socialNetworkName	= null;
	public /*string*/	$name				= null;
	public /*string*/	$firstName			= null;
	public /*string*/	$lastName			= null;
	public /*string*/	$link				= null;
	public /*string*/	$birthday			= null;
	public /*string*/	$hometown			= null;
	public /*string*/	$gender				= null;
	public /*int*/		$buddyList			= null;
	public /*int*/		$subscribtionList	= null;
	public /*int*/		$reputation			= null;
	public /*int*/		$session			= null;
	public /*int*/		$transactionList	= null;
	public /*string*/	$email				= null;
	public /*string*/	$password			= null;
	public /*string*/	$profilePicture		= null;
	public /*bool*/ function equals(Profile $profile)
	{
		return $this->mymedID			=== $profile->mymedID
			&& $this->socialNetworkID	=== $profile->socialNetworkID
			&& $this->socialNetworkName	=== $profile->socialNetworkName
			&& $this->name				=== $profile->name
			&& $this->firstName			=== $profile->firstName
			&& $this->lastName			=== $profile->lastName
			&& $this->link				=== $profile->link
			&& $this->birthday			=== $profile->birthday
			&& $this->hometown			=== $profile->hometown
			&& $this->gender			=== $profile->gender
			&& $this->email				=== $profile->email
			&& $this->password			=== $profile->password
			&& $this->profilePicture	=== $profile->profilePicture;
	}
}
?>