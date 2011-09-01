<?php
require_once __DIR__.'/IContentObject.class.php';
/**
 * An interface to define the page's main content
 */
interface IContainerObject extends IContentObject
{
	/*
	 * Print the page's 'title
	 * @param int $deep if content is in a container $deep=x give the parent's 
	 * 				container's title of degree n-x where n is the number of 
	 * 				containers. $deep=null give the content's title.
	 * @return string the title or null if no title selected
	 */
	//public /*string*/ function getTitle(/*int*/ $deep=null);
	/**
	 * set container's' content
	 * @param IContentObject $content	new content
	 */
	public /*void*/ function setContent(IContentObject $content);
}
?>
