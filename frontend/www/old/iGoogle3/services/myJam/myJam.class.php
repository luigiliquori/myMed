<?php
class myJam extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myJam';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<style>
		#myJam {
    		width:100%;
    		height:100%;
		}
		#mapcanvas {
			min-height: 248px;
    		width:100%;
    		height:100%;
    		overflow:auto;
    		position:relative;
		}
		</style>
		<script type="text/javascript" src="services/myJam/map.js"></script>
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
				<div id="mapcanvas"></div>
				<div id="status"></div>
				<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;callback=launchGeolocation"></script>
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
