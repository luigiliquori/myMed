<?php
require_once __DIR__.'/ContentObject.class.php';
require_once __DIR__.'/IContainerObject.class.php';
/**
 * A class to define a container of ContentObject
 */
abstract class ContainerObject extends ContentObject implements IContainerObject
{
	protected $content;
	/**
	 * Get container's' name (default : className)
	 * @return string Container's name'
	 */
	protected /*string*/ function getName()
	{
		return get_class($this);
	}
	/**
	 * Print the page's 'title
	 * @param int $deep if content is in a container $deep=x give the parent's 
	 * 				container's title of degree n-x where n is the number of 
	 * 				containers. $deep=null give the content's title.
	 * @return string	the title or null if no title selected
	 */
	public /*string*/ function getTitle(/*int*/ $deep=null)
	{
		if($this->content!==null)
		{
			if($deep===null)
				return $this->content->getTitle();
			elseif($deep === 0)
				return $this->getName();
			elseif($this->content instanceof IContainerObject)
				return $this->content->getTitle($deep-1);
			elseif($deep === 1)
				return $this->content->getTitle();
		}
		elseif($deep===null)
			return $this->getName();
		return null;
	}
	/**
	 * set container's' content
	 * @param IContentObject $content	new content
	 */
	public /*void*/ function setContent(IContentObject $content)
	{
		$this->content = $content;
	}
}
?>
