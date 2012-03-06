<?php
/**
 * An interface to define a container of ContentObject
 */
interface IContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title (mustn't be null, could be empty)
	 */
	public /*string*/ function getTitle();
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags();
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags();
	/**
	 * Print page's main content when page called with GET method
	 */
	public /*void*/ function contentGet();
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost();
}
?>
