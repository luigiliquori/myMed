<?php
require_once __DIR__.'/../socialNetworkAPIs/GlobalConnexion.class.php';
class TemplateManager extends GlobalConnexion
{
	private /*ContentObject*/ $content;
	private /*string*/ $template;
	public function __construct(ContentObject $content=null, /*string*/ $template="home")
	{
		parent::__construct();
		$this->content	= $content;
		$this->template	= $template;
	}
	/**
	 * Print the title of the page
	 */
	private /*void*/ function getTitle()
	{
		if($this->content!==null)
			echo $this->content->getTitle();
	}
	private /*void*/ function getServiceName()
	{
		if($this->content!==null)
			echo get_class($this->content);
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		parent::headTags();
		if($this->content!==null)
			$this->content->headTags();
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags()
	{
		parent::scriptTags();
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
	public /*void*/ function setContent(ContentObject $content)
	{
		$this->content = $content;
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
			header('Location:'.$_SERVER["REQUEST_URI"]);
			exit;
		}
	}
}
?>