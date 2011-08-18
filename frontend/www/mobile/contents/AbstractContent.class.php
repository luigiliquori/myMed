<?php
abstract class AbstractContent {
	
	protected /*string*/ $top;
	protected /*string*/ $left;
	protected /*string*/ $right;
	protected /*string*/ $bottom;
	protected /*string*/ $width;
	protected /*string*/ $height;
	
	public function __construct() {
		$this->top = "null";
		$this->bottom = "null";
		$this->left = "null";
		$this->right = "null";
		$this->width = "null";
		$this->height = "null";
	}
	
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags(){}
	
	/**
	 * Print the Design css of each content
	 */
	public /*void*/ function cssDesign(){
		if($this->top != "null") { 
			echo "top: " . $this->top . ";";
		}
		if($this->bottom != "null") {
			echo "bottom: " . $this->bottom . ";";
		}
		if($this->left != "null") {
			echo "left: " . $this->left . ";";
		}
		if($this->right != "null") {
			echo "right: " . $this->right . ";";
		}
		if($this->height != "null") {
			echo "height: " . $this->height . ";";
		}
		if($this->width != "null") {
			echo "width: " . $this->width . ";";
		}
	}
	
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	
	/**
	 * Print page's main content when page called with GET method
	 */
	public abstract /*void*/ function contentGet();

	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
	
	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public /*void*/ function setTop(/*string*/ $top="0px"){
		$this->top = $top;
	}

	public /*void*/ function setBottom(/*string*/ $bottom="0px"){
		$this->bottom = $bottom;
	}
	
	public /*void*/ function setRight(/*string*/ $right="0px"){
		$this->right = $right;
	}
	
	public /*void*/ function setLeft(/*string*/ $left="0px"){
		$this->left = $left;
	}
	
	public /*void*/ function setHeight(/*string*/ $height="0px"){
		$this->height = $height;
	}
	
	public /*void*/ function setWidth(/*string*/ $width="0px"){
		$this->width = $width;
	}
}
?>