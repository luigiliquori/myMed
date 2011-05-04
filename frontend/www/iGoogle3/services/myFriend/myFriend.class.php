<?php
class myFriend extends ContentObject
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
		#myFriend {
			min-height: 161px;
    		width:100%;
    		height:100%;
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
		if(isset($friends)&&count($friends)>0)
		{
			echo '
				<ul>';
			foreach ($friends as $value)
				echo '
					<li><a href=http://www.facebook.com/#!/profile.php?id='.$value->id.'">'.$value->name.'</a></li>';
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
