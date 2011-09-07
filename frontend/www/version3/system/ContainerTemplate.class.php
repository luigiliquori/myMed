<?php
require_once __DIR__.'/ContainerObject.class.php';
/**
 * A class to define sub-templates
 */
abstract class ContainerTemplate extends ContainerObject
{
	protected /*string*/ $name;
	/**
	 * @param string $name	Container's title (default : className without begin string "Template")
	 * @param IContentObject $content	content
	 */
	public function __construct(/*string*/ $name=null, IContentObject $content=null)
	{
		$this->name	= ($name!==null)?$name:strtolower(preg_replace('#^Template#', '', get_class($this)));
		$this->content = $content;
	}
	/**
	 * Get container's' name (default : className)
	 * @return string Container's name'
	 */
	protected /*string*/ function getName()
	{
		return $this->name;
	}
	public /*void*/ function contentGet()
	{
		//$this->content->contentGet();
		require __DIR__.'/subtemplates/'.$this->name.'.body.php';
	}
	public /*void*/ function contentPost()
	{
		if($this->content)
			$this->content->contentPost();
	}
	public /*void*/ function headTags()
	{
		if(is_file(__DIR__.'/subtemplates/'.$this->name.'.head.php'))
			require __DIR__.'/subtemplates/'.$this->name.'.head.php';
		if($this->content)
			$this->content->headTags();
	}
	public /*void*/ function scriptTags()
	{
		if(is_file(__DIR__.'/subtemplates/'.$this->name.'.script.php'))
			require __DIR__.'/subtemplates/'.$this->name.'.script.php';
		if($this->content)
			$this->content->scriptTags();
	}
}
?>
