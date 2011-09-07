<?php
require_once __DIR__.'/ContentObject.class.php';
/**
 * A class to define sub-templates
 */
abstract class ContentTemplate extends ContentObject
{
	protected /*string*/ $name;
	/**
	 * @param string $name	Content's title (default : className without begin string "Template")
	 */
	public function __construct(/*string*/ $name=null)
	{
		$this->name	= ($name!==null)?$name:strtolower(preg_replace('#^Template#', '', get_class($this)));
	}
	/**
	 * Method to define the title of the page
	 * @return string	Content Title (mustn't be null, could be empty)
	 */
	public /*string*/ function getTitle()
	{
		return $this->name;
	}
	/**
	 * do nothing only for IContentObject compatibility in main.php
	 * @param IContentObject $content	not used
	 */
	public /*void*/ function setContent(IContentObject $content){}
	public /*void*/ function contentGet()
	{
		//$this->content->contentGet();
		require __DIR__.'/subtemplates/'.$this->name.'.body.php';
	}
	public /*void*/ function headTags()
	{
		if(is_file(__DIR__.'/subtemplates/'.$this->name.'.head.php'))
			require __DIR__.'/subtemplates/'.$this->name.'.head.php';
	}
	public /*void*/ function scriptTags()
	{
		if(is_file(__DIR__.'/subtemplates/'.$this->name.'.script.php'))
			require __DIR__.'/subtemplates/'.$this->name.'.script.php';
	}
}
?>
