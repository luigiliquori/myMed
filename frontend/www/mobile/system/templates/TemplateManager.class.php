<?php
class TemplateManager {
	private /*string*/ $template;
	
	public function __construct(/*string*/ $template="login") {
		$this->template	= $template;
	}

	/**
	 * Methode to select template for the current page
	 * @param string $name	name of the template's file without .php
	 */
	public /*void*/ function selectTemplate(/*string*/ $name) {
		$this->template = $name;
	}
	
	/**
	 * Methode to call template for the current page
	 * @param string $name	name of the template's file without .php if not exists use selected Template
	 */
	public /*void*/ function callTemplate(/*string*/ $name=null) {
		if($name!==null) {
			$this->selectTemplate($name);
		}
		require_once(dirname(__FILE__).'/' . $this->template . '/main.php');
	}
}
?>