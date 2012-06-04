<?php
require_once __DIR__.'/../JSon.class.php';

/**
 * @author lvanni
 */
abstract class MReputationBean extends JSon
{
	/**
	 * Used for the hash code
	 */
	public /*int*/	$PRIME;
	/**
	 * The value of the reputation
	 */
	public /*double*/	$value;
	/**
	 * The number of raters
	 */
	public /*int*/	$nbRaters;
}
?>
