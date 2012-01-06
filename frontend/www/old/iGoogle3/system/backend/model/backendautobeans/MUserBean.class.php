<?php
require_once __DIR__.'/../JSon.class.php';

/**
 * This class represent an user profile
 * @author lvanni
 */
abstract class MUserBean extends JSon
{
	/**
	 * Used for the hash code
	 */
	public /*int*/	$PRIME;
	/**
	 * USER_ID
	 */
	public /*String*/	$id;
	/**
	 * AUTHENTICATION_ID
	 */
	public /*String*/	$login;
	public /*String*/	$email;
	public /*String*/	$name;
	public /*String*/	$firstName;
	public /*String*/	$lastName;
	public /*String*/	$link;
	public /*long*/	$birthday;
	public /*String*/	$hometown;
	public /*String*/	$gender;
	public /*long*/	$lastConnection;
	public /*String*/	$profilePicture;
	/**
	 * USER_LIST_ID
	 */
	public /*String*/	$buddyList;
	/**
	 * APPLICATION_LIST_ID
	 */
	public /*String*/	$subscribtionList;
	/**
	 * REPUTATION_ID
	 */
	public /*String*/	$reputation;
	/**
	 * SESSION_ID || null
	 */
	public /*String*/	$session;
	/**
	 * INTERACTION_LIST_ID
	 */
	public /*String*/	$interactionList;
	public /*String*/	$socialNetworkID;
	public /*String*/	$socialNetworkName;
}
?>
