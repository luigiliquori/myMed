<?php 

require_once 'system/beans/MUserBean.class.php';

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
	public /*void*/ function handleLogin();
	
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