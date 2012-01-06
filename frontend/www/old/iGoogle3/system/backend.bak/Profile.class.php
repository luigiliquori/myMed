<?php
require_once __DIR__.'/JSon.class.php';
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
	public /*bool*/ function equalsOrNull(Profile $profile)
	{
		return $this->mymedID			=== $profile->mymedID
			&& $this->socialNetworkID	=== $profile->socialNetworkID
			&& $this->socialNetworkName	=== $profile->socialNetworkName
			&& ($this->name				=== $profile->name		||$profile->profilePicture	=== null)
			&& ($this->firstName		=== $profile->firstName	||$profile->profilePicture	=== null)
			&& ($this->lastName			=== $profile->lastName	||$profile->profilePicture	=== null)
			&& ($this->link				=== $profile->link		||$profile->profilePicture	=== null)
			&& ($this->birthday			=== $profile->birthday	||$profile->profilePicture	=== null)
			&& ($this->hometown			=== $profile->hometown	||$profile->profilePicture	=== null)
			&& ($this->gender			=== $profile->gender	||$profile->profilePicture	=== null)
			&& ($this->email			=== $profile->email		||$profile->profilePicture	=== null)
			&& $this->password			=== $profile->password
			&& ($this->profilePicture	=== $profile->profilePicture	||$profile->profilePicture	=== null);
	}
}
?>
