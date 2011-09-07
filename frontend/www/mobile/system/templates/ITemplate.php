<?php 
/**
 * 
 * Represent the format of a template template
 * @author lvanni
 *
 */
interface ITemplate {
	
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader();
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent();
	
   /**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter();
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate();
}
?>