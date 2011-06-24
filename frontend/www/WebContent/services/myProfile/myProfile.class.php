<?php
class myProfile extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myProfile';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<style>
		#myProfile {
			min-height: 248px;
    		width:100%;
    		height:100%;
    		overflow:auto;
		}
		#myProfile object {
    		width:100%;
    		height:100%;
    		overflow:auto;
    		display:block;
    		border:0 none;
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
				<object data="<?=$_SESSION['user']->link?>" type="text/html"></object>
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
