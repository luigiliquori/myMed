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
