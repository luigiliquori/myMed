<?php
require_once __DIR__.'/../JSon.class.php';

/**
 * This class represent an user profile
 * @author lvanni
 */
abstract class MApplicationViewBean extends JSon
{
	public /*String*/	$name;
	/**
	 * ID_CATEGORY
	 */
	public /*String*/	$category;
	public /*long*/	$dateOfCreation;
	public /*String*/	$description;
	public /*String*/	$icon;
	public /*String*/	$background;
	/**
	 * USER_LIST_ID
	 */
	public /*String*/	$publisherList;
	/**
	 * USER_LIST_ID
	 */
	public /*String*/	$subscriberList;
	/**
	 * USER_ID
	 */
	public /*String*/	$administrator;
	/**
	 * ONTOLOGY_LIST_ID
	 */
	public /*String*/	$ontologies;
	/**
	 * APPLICATION_MODEL_ID
	 */
	public /*String*/	$model;
	/**
	 * APPLICATION_CONTROLLER_ID
	 */
	public /*String*/	$controller;
}
?>
