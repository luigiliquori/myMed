<?php
/**
 * An class to define a 404 error
 */
class ServiceNotFound extends ContentObject
{
	public function __construct()
	{
		header("Status: 404 Not Found", false, 404);
	}
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return '404 Not Found';}
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags(){}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print page's main content when page called with GET method
	 */
	public /*void*/ function contentGet()
	{
		echo 'Content Not Found !';
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
