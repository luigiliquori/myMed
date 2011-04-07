<?php
// @todo warning $_SESSION['user'] is now an Array
/**
 * A class to define a type of login to myMed
 * @author blanchard
 */
abstract class Connexion
{
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags(){}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print the connexion's button
	 */
	public abstract /*void*/ function button();
}
?>