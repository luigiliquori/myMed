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
    		background-image		: url('services/myJob/background.jpg');
    		background-repeat		: no-repeat;
    		background-size			: 100% auto;
    		-moz-background-size	: 100% auto;
    		-o-background-size		: 100% auto;
    		-khtml-background-size	: 100% auto;
		}
		#myJob form {
			margin	: 2em 1ex;
		}
		#myJob form div {
			float	: left;
			width	: 30%;
			margin	: 1%;
		}
		#myJob form div.buttons {
			float	: none;
			clear	: left;
			width	: auto;
		}
		#myJob form label ,
		#myJob form input {
			display	: block;
			width	: 100%;
		}
		#myJob form label {
			font-weight	: bold;
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
					<div title="ex: Chef de projet">
						<label for="job_title">Titre du poste</label>
						<input type="text" id="job_title" name="title" placeholder="ex: Chef de projet" />
					</div>
					<div title="ex: santé, JAVA">
						<label for="job_keywords">Mots-clés</label>
						<input type="text" id="job_keywords" name="keywords" placeholder="ex: santé, JAVA" />
					</div>
					<div title="ex: ville, région, code postal">
						<label for="job_place">Lieu</label>
						<input type="text" id="job_place" name="place" placeholder="ex: ville, région, code postal" />
					</div>
					<div class="buttons">
						<button type="submit">Rechercher</button>
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
