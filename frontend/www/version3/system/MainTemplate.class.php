<?php
require_once __DIR__.'/IContentObject.class.php';
require_once __DIR__.'/IContainerObject.class.php';
/**
 * A class to manage templates, contents and login
 */
class MainTemplate
{
	private /*IContentObject*/ $content;
	private /*string*/ $template;
	private /*GlobalConnexion*/ $connexions;
	public function __construct(IContentObject $content=null, /*string*/ $template="main")
	{
		$this->content		= $content;
		$this->template		= $template;
		$this->connexions	= GlobalConnexion::getInstance();
	}
	/**
	 * Print the page's 'title
	 * @param int $deep if content is in a container $deep=x print the parent's 
	 * 				container's title of degree n-x where n is the number of 
	 * 				containers. $deep=null print the content's title.
	 * @param string $prefix string to print before title if title not empty
	 * @return false if no title selected
	 */
	private /*bool*/ function getTitle(/*int*/ $deep=null, /*string*/ $prefix='')
	{
		$title = null;
		if($this->content!==null)
		{
			if($deep===null)
				$title	= $this->content->getTitle();
			elseif($this->content instanceof IContainerObject)
				$title	= $this->content->getTitle($deep);
			elseif($deep === 0)
				$title	= $this->content->getTitle();
		}
		if($title != '')
			echo $prefix.$title;
		return ($title !== null);
	}
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags()
	{
		$this->connexions->headTags();
		if($this->content!==null)
			$this->content->headTags();
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags()
	{
		$this->connexions->scriptTags();
		if($this->content!==null)
			$this->content->scriptTags();
	}
	/**
	 * Print the main content of the page
	 */
	private /*void*/ function content()
	{
		if($this->content!==null)
			$this->content->contentGet();
	}
	/**
	 * set page's content
	 * @param $content	content to print
	 */
	public /*void*/ function setContent(IContentObject $content)
	{
		$this->content = $content;
	}
	/**
	 * @return true if content not null
	 */
	public /*bool*/ function isContent()
	{
		return $this->content !== null;
	}
	/**
	 * Methode to select template for the current page
	 * @param string $name	name of the template's file without .php
	 */
	public /*void*/ function selectTemplate(/*string*/ $name)
	{
		$this->template = $name;
	}
	/**
	 * Methode to call template for the current page
	 * @param string $name	name of the template's file without .php if not exists use selected Template
	 */
	public /*void*/ function callTemplate(/*string*/ $name=null)
	{
		if($name!==null)
			$this->selectTemplate($name);
		if($_SERVER["REQUEST_METHOD"] == 'GET')
			require(__DIR__.'/templates/'.$this->template.'.php');
		else
		{
			if( ($_SERVER["REQUEST_METHOD"] == 'POST')&&($this->content!==null) )
				$this->content->contentPost();
			httpRedirect($_SERVER["REQUEST_URI"]);
		}
	}
}
?>
