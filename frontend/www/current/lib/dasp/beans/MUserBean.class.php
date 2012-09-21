<?php
/*
 * Copyright 2012 INRIA
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*     http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
class MUserBean {

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
	public /*long*/		$birthday;
	public /*String*/	$hometown;
	public /*String*/	$gender;
	public /*long*/		$lastConnection;
	public /*String*/	$profilePicture;
	/**
	 * USER_LIST_ID
	 */
	public /*String*/	$buddyList;
	/**
	 * APPLICATION_LIST_ID
	 */
	public /*String*/	$applicationList;
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
	
	/** lang **/
	
	public /*String*/	$lang;
	
	
	public static function constructFromGoogleOAuth($user){
		$user['email'] = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
		$user['profilePicture'] = filter_var($user['picture'], FILTER_VALIDATE_URL);
		
		if (isset($user['link']))
			$user['link'] = filter_var($user['link'], FILTER_VALIDATE_URL);
		if (isset($user['given_name']))
			$user['firstName'] = $user['given_name'];
		if (isset($user['family_name']))
			$user['lastName'] = $user['family_name'];
		if (isset($user['locale']))
			$user['lang'] = substr($user['locale'], 0, 2);
		
		$user['id'] = 'MYMED_'.$user['email'];
		
		$user['socialNetworkName'] = 'Google-OAuth';
		
		return (object) array_intersect_key($user, get_class_vars(__CLASS__));
	}
	
	public static function constructFromFacebookOAuth($user){
		if (isset($user['link']))
			$user['link'] = filter_var($user['link'], FILTER_VALIDATE_URL);
		if (isset($user['first_name']))
			$user['firstName'] = $user['first_name'];
		if (isset($user['last_name']))
			$user['lastName'] = $user['last_name'];
		if (isset($user['locale']))
			$user['lang'] = substr($user['locale'], 0, 2);
	
		$user['socialNetworkName'] = 'Facebook-OAuth';
	
		return (object) array_intersect_key($user, get_class_vars(__CLASS__));
	}
	
	public static function constructFromTwitterOAuth($user){
		if (isset($user['link']))
			$user['link'] = filter_var($user['link'], FILTER_VALIDATE_URL);
		if (isset($user['profile_image_url']))
			$user['profilePicture'] = filter_var($user['profile_image_url'], FILTER_VALIDATE_URL);
	
		$user['socialNetworkName'] = 'Twitter-OAuth';
	
		return (object) array_intersect_key($user, get_class_vars(__CLASS__));
	}
	
	public static function constructFromOpenId($user){
		foreach ($user as $k=>$v){
			if(strpos($k, 'first')!==false)
				$user['firstName'] = $v[0];
			else if(strpos($k, 'email')!==false)
				$user['email'] = $v[0];
			else if(strpos($k, 'last')!==false)
				$user['lastName'] = $v[0];
			else if(strpos($k, 'language')!==false)
				$user['lang'] = substr($v[0], 0, 2);
		}
		$user['name'] = $user['firstName'] . ' ' .$user['lastName'];
		
		$user['id'] = 'MYMED_'.$user['email'];
		
		$user['socialNetworkName'] = 'OpenID';

		return (object) array_intersect_key($user, get_class_vars(__CLASS__));
	}
	
}

?>
