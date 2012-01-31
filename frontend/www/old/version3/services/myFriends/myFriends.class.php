<?php
class myFriends extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myFriend';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<style>
		#myFriends {
			height	: 100%;
			width	: 100%;
			overflow:auto;
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
		if(isset($_SESSION['friends'])&&count($_SESSION['friends'])>0)
		{
			usort($_SESSION['friends'], function($f1, $f2)
			{
				return strcasecmp($f1['displayName'], $f2['displayName']);
			});
			echo '
				<ul>';
			foreach ($_SESSION['friends'] as $value)
				if(isset($value['profileUrl']))
					echo '
					<li><a href="'.htmlspecialchars($value['profileUrl']).'">'.htmlspecialchars($value['displayName']).'</a></li>';
				else
					echo '
					<li>'.htmlspecialchars($value['displayName']).'</li>';
			echo '
				</ul>';
		}
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
