<?php
class Profile
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
	public /*int*/		$buddy_list			= null;
	public /*int*/		$subscribtionList	= null;
	public /*int*/		$reputation			= null;
	public /*int*/		$session			= null;
	public /*int*/		$transactionList	= null;
	public /*string*/	$email				= null;
	public /*string*/	$possword			= null;
	public /*string*/	$profilePicture	= null;
	public static function __set_state($array)
	{
		$obj = new Profile();
		$obj->mymedID			= $array['mymedID'];
		$obj->socialNetworkID	= $array['socialNetworkID'];
		$obj->socialNetworkName	= $array['socialNetworkName'];
		$obj->name				= $array['name'];
		$obj->firstName			= $array['firstName'];
		$obj->lastName			= $array['lastName'];
		$obj->link				= $array['link'];
		$obj->birthday			= $array['birthday'];
		$obj->hometown			= $array['hometown'];
		$obj->gender			= $array['gender'];
		$obj->buddyList			= $array['buddyList'];
		$obj->subscribtionList	= $array['subscribtionList'];
		$obj->reputation		= $array['reputation'];
		$obj->session			= $array['session'];
		$obj->transactionList	= $array['transactionList'];
		$obj->email				= $array['email'];
		$obj->possword			= $array['possword'];
		$obj->profilePicture	= $array['profilePicture'];
		return $obj;
	}
	public /*string*/ function __toString()
	{
		return json_encode($this);
	}
}
?>