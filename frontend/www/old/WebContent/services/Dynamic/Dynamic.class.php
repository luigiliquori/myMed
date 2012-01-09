<?php
class Dynamic extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle()
	{
		return $_GET['service'];
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags(){}
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
				<div id="warning">
					<h1>myMed v1.0 alpha</h1>
					<h3>
						Sorry this feature is not yet available... 
					</h3>
					<h3>
						For more details please contact us: <a href="mailto:infomymed@mymed.fr">infomymed@mymed.fr</a>
						or visit our webSite <a href="http://www.mymed.fr">www.mymed.fr</a>
					</h3>
					<a href="?">&lt; Back to Desktop</a>
				</div>
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