<?php
class myJob extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myJob';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<style>
		#myJob {
			min-height: 161px;
    		width:100%;
    		height:100%;
    		overflow:auto;
    		background-image: url("style/img/embauche.jpg");
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
				<table>
					<tr>
						<td>
							<b>Titre du poste</b><br />
							<input type="text" style="width: 120px;" /><br />
							ex: Chef de projet
						</td>
						<td>
							<b>Mots-clés</b><br />
							<input type="text" style="width: 120px;" /><br />
							ex: santé, JAVA
						</td>
						<td>
							<b>Lieu</b><br />
							<input type="text" style="width: 120px;" /><br />
							ex: ville, région, code postal
						</td>
					</tr>
					<tr>
						<td><input type="submit" value="Rechercher" /></td>
					</tr>
				</table>
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
