<?php
class myAngel extends ContentObject
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
		#myAngel ,#myAngel * {
			min-height: 248px;
    		width:100%;
    		height:100%;
		}
		#myAngel button {
			border					: 0 none;
			background-image		: url("services/myAngel/button.png");
			background-position		: center;
			background-repeat		: no-repeat;
			background-color		: #000000;
			background-size			: auto 100%;
			-moz-background-size	: auto 100%;
			-o-background-size		: auto 100%;
			-khtml-background-size	: auto 100%;
		}
		#myAngel button:hover {
			background-image		: url("services/myAngel/button_hover.png");
		}
		#myAngel button span {
			display	: none;
		}
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
		?>
		<form method="post" action="#" onsubmit="return false;">
			<div>
				<button type="submit"><span>Alert</span></button>
			</div>
		</form>
		<?php
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
