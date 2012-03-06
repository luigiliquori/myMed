<?php
class myBigApp extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myBigApp';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<style>
		#myBigApp {
			width:100%;
			height:100%;
		}/*
		#myBigApp button {
			height:100%;
			width:100%;
			background-color:red;
			border-radius:1em;
			border: solid 1px  #800	background-image:-moz-linear-gradient(top,#f00, #c00);
		}*/
		</style>
<?php
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print page's main content when page called with GET method
	 */
	public /*void*/ function contentGet()
	{
		/*?>
		<button>Alert</button>
		<?php*/
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
