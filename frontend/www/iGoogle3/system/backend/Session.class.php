<?php
require_once dirname(__FILE__).'/JSon.class.php';
class Session extends JSon
{
	public /*int*/		$mymedID				= null;
	public /*string*/	$currentApplications	= null;
	public /*int*/		$timeout				= null;
	public /*bool*/		$isP2P					= null;
	public /*string*/	$ip						= null;
	public /*int*/		$port					= null;
}
?>
