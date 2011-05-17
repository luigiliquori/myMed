<?php
class Profile
{
	public /*string*/	$id					= null;
	public /*string*/	$social_network		= null;
	public /*string*/	$name				= null;
	public /*string*/	$first_name			= null;
	public /*string*/	$last_name			= null;
	public /*string*/	$link				= null;
	public /*string*/	$birthday			= null;
	public /*string*/	$hometown			= null;
	public /*string*/	$gender				= null;
	public /*int*/		$buddy_list			= null;
	public /*int*/		$subscribtion_list	= null;
	public /*int*/		$reputation			= null;
	public /*int*/		$session			= null;
	public /*int*/		$transaction_list	= null;
	public /*string*/	$email				= null;
	public /*string*/	$possword			= null;
	public /*string*/	$profile_picture	= null;
	public static function __set_state($array) // Depuis PHP 5.1.0
	{
		$obj = new Profile();
		$obj->id				= $array['id'];
		$obj->social_network	= $array['social_network'];
		$obj->name				= $array['name'];
		$obj->first_name		= $array['first_name'];
		$obj->last_name		= $array['last_name'];
		$obj->link				= $array['link'];
		$obj->birthday			= $array['birthday'];
		$obj->hometown			= $array['hometown'];
		$obj->gender			= $array['gender'];
		$obj->buddy_list		= $array['buddy_list'];
		$obj->subscribtion_list= $array['subscribtion_list'];
		$obj->reputation		= $array['reputation'];
		$obj->session			= $array['session'];
		$obj->transaction_list	= $array['transaction_list'];
		$obj->email			= $array['email'];
		$obj->possword			= $array['possword'];
		$obj->profile_picture	= $array['profile_picture'];
		return $obj;
	}
}
?>