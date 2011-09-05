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
			width:100%;
			height:100%;
			overflow:auto;
		}
		#myProfile div.leftCol {
			min-height		: 248px;
			height			: 100%;
			float			: left;
			background-color: #edf2f4;
		}
		#myProfile div.rightCol {
			overflow	: auto;
			display		: table;
			padding		: 0.5ex;
		}
		#myProfile div.rightCol>* {
			display		: table-row;
		}
		#myProfile div.rightCol>*>* {
			display			: table-cell;
			padding			: 0.2ex;
			vertical-align: bottom;
		}
		#myProfile div.rightCol span.title {
			font-weight		: bold;
			vertical-align	: top;
		}
		#myProfile div.rightCol button {
			position	: absolute;
			padding		: 0.2ex 1ex;;
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
				<div class="leftCol">
					<img width="200px" alt="profile picture" src="<?= $_SESSION['user']->profilePicture?$_SESSION['user']->profilePicture:'http://graph.facebook.com//picture?type=large' ?>" />
				</div>
				<div class="rightCol">
					<div>
						<span class="title">Nom&#160;:</span>
						<span><?= $_SESSION['user']->name?$_SESSION['user']->name:'Unknown' ?></span>
					</div>
					<div>
						<span class="title">Sexe&#160;:</span>
						<span><?= $_SESSION['user']->gender?$_SESSION['user']->gender=='F'?'Femme':'Homme':'Unknown' ?></span>
					</div>
					<div>
						<span class="title">Date de naissance&#160;:</span>
						<span><?= $_SESSION['user']->birthday?$_SESSION['user']->birthday:'Unknown' ?></span>
					</div>
					<div>
						<span class="title">Ville&#160;:</span>
						<span><?= $_SESSION['user']->hometown?$_SESSION['user']->hometown:'Unknown' ?></span>
					</div>
					<div>
					   <span class="title">Profile&#160;:</span>
					   <span>
					   <?php
					   	if($_SESSION['user']->link)
					   		echo '
					   		<a href="'.$_SESSION['user']->link.'">'.$_SESSION['user']->socialNetworkName.'</a>';
					   	else
					   		echo '
					   		'.$_SESSION['user']->socialNetworkName;
					   ?>

					   </span>
					</div>
					<div>
					   <button type="button">modifier le profile</button>
					</div>
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
