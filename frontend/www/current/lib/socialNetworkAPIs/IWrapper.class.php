<?php 

require_once dirname(__FILE__).'/../dasp/beans/MUserBean.class.php';

/**
 *
 * Represent a Social Network Wrapper
 * @author lvanni
 *
 */
interface IWrapper {

	/**
	 * Return the social network name of the wrapper
	 */
	public /*String*/ function getSocialNetworkName();

	/**
	 * Handle a login request
	 */
	public /*void*/ function handleAuthentication();

	/**
	 * get the login url for the social network button
	 */
	public /*String*/ function getLoginUrl();

	/**
	 * get the logout url for the social network button
	 */
	public /*String*/ function getLogoutUrl();

}

?>