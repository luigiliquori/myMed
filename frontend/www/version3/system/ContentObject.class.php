<?php
require_once __DIR__.'/IContentObject.class.php';
/**
 * A class to define the page's main content
 */
abstract class ContentObject implements IContentObject
{
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags(){}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
