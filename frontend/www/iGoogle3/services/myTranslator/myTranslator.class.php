<?php
class myTranslator extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myTranslator';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<style>
		#myTranslator {
			min-height: 161px;
    		width:100%;
    		height:100%;
    		overflow:auto;
    		background-image: url('style/img/traduction.jpg');
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
				<div style="position: relative; top:10px; left:10px; width: 400px">
					<br /><br />
					<input type="text" name="textATraduire" value="Saisissez le text à traduire ici." style="width: 380px;" /><br /><br />
					<select name="langueOrigin">
							<option value="Francais" selected="selected">Francais</option>
							<option value="Anglais">Anglais</option>
							<option value="Italien">Italien</option>
					</select>
					&gt;&gt;
					<select name="langueTraduite">
							<option value="Italien" selected="selected">Italien</option>
							<option value="Francais">Francais</option>
							<option value="Anglais">Anglais</option>
					</select>
					<input type="submit" value="Traduire" />
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
