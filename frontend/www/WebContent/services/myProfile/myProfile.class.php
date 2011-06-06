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
				<div style="width: 200px; height: 245px; background-color: #edf2f4;">
					<img width="200px" alt="profile picture" src="<?= $_SESSION['user']['profile_picture']?$_SESSION['user']['profile_picture']:'http://graph.facebook.com//picture?type=large' ?>" /><br />
					<br />
					<!-- USER INFOMATION -->
					<table style="position: absolute; left:200px; top:0px; text-align: left;">
					<tr>
					  <th>Name:</th>
					  <td> <?= $_SESSION['user']['name']?$_SESSION['user']['name']:'Unknown' ?></td>
					</tr>
					<tr>
					  <th>Gender:</th>
					  <td> <?= $_SESSION['user']['gender']?$_SESSION['user']['gender']:'Unknown' ?></td>
					</tr>
					<tr>
					  <th>Langage:</th>
					  <td> <?= $_SESSION['user']['locale']?$_SESSION['user']['locale']:'Unknown' ?></td>
					 </tr>
					<tr>
					   <th>Profile from:</th>
					   <td> <?= $_SESSION['user']['social_network'] ?></td>
					  </tr>
					<tr>
					   <td colspan="2"><input type="button" value="modifier le profile" /></td>
					</tr>
					</table>
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
