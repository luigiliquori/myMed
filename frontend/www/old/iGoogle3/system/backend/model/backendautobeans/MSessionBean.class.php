<?php
require_once __DIR__.'/../JSon.class.php';

abstract class MSessionBean extends JSon
{
	/**
	 * Used for the calculation of the hash code
	 */
	public /*int*/	$PRIME;
	/**
	 * SESSION_ID
	 */
	public /*String*/	$id;
	/**
	 * USER_ID
	 */
	public /*String*/	$user;
	/**
	 * APPLICATION_LIST_ID
	 */
	public /*String*/	$currentApplications;
	public /*long*/	$timeout;
	public /*boolean*/	$isP2P;
	public /*String*/	$ip;
	public /*int*/	$port;
}
?>
