<?php
require_once __DIR__.'/../JSon.class.php';

/**
 * @author lvanni
 */
abstract class MInteractionBean extends JSon
{
	/**
	 * INTERACTION_ID
	 */
	public /*String*/	$id;
	/**
	 * APPLICATION_ID
	 */
	public /*String*/	$application;
	/**
	 * USER_ID
	 */
	public /*String*/	$producer;
	/**
	 * USER_ID
	 */
	public /*String*/	$consumer;
	public /*long*/	$start;
	public /*long*/	$end;
	public /*double*/	$feedback;
	public /*int*/	$snooze;
	/**
	 * INTERACTION_LIST_ID
	 */
	public /*String*/	$complexInteraction;
}
?>
