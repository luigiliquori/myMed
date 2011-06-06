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
    		background-image: url('services/myTranslator/background.jpg');
    		background-size			: 100% auto;
    		-moz-background-size	: 100% auto;
    		-o-background-size		: 100% auto;
    		-khtml-background-size	: 100% auto;
		}
		#myTranslator form {
			margin-top	: 2em;
			padding	: 1ex;
		}
		#myTranslator form div {
			margin	: 2ex 1ex;
		}
		#myTranslator form div input {
			width	: 100%;
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
				<form method="get" action="#">
					<div><input type="text" name="word" placeholder="Saisissez le text à traduire ici." /></div>
					<div>
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
						<button type="submit">Traduire</button>
					</div>
				</form>
				<script type="text/javascript">$("[placeholder]").textPlaceholder();</script>
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
