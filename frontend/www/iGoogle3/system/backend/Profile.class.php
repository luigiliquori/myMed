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
	public /*string*/	$possword			= null;
	public /*string*/	$profilePicture		= null;
}
?>