<?php
require_once __DIR__.'/../JSon.class.php';

/**
 * @author lvanni
 */
abstract class MAuthenticationBean extends JSon
{
	/**
	 * AUTHENTICATION_ID
	 */
	public /*String*/	$login;
	/**
	 * USER_ID
	 */
	public /*String*/	$user;
	/**
	 * sha256(string)
	 */
	public /*String*/	$password;
}
?>
