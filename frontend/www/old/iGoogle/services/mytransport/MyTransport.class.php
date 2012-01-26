<?php
class MyTransport extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle()
	{
		return 'MyTransport';
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		//for compatibility with XHTML don't use http://maps.google.com/maps/api/js?sensor=false 
		//but http://maps.google.com/maps/api/js?sensor=false&callback=launchGeolocation
?>
		<link rel="stylesheet" href="services/mytransport/css/jquery.autocomplete.css" />
		<script src="jquery/dist/jquery.min.js"></script>
		<script src="services/mytransport/javascript/map.js"></script>
		<script src="services/mytransport/javascript/geo_autocomplete.js"></script>
		<script src="services/mytransport/javascript/jquery.autocomplete_geomod.js"></script>
<?php
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags()
	{
?>
		<script src="http://maps.google.com/maps/api/js?sensor=false&amp;callback=launchGeolocation"></script>
<?php
	}
	public /*void*/ function contentGet()
	{
		echo '<div style="overflow:hidden;width:100%;height:100%;position: relative;background-color:#415B68;">';
		if(isset($_GET['publish']))
		{
			$this->publish();
		}
		else
		{
			$this->find();
		}
		require __DIR__.'/footer.html.php';
		echo '</div>';
	}
	private /*void*/ function find()
	{
		if(isset($_GET["search"]))
		{
			$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
			$id = trim(file_get_contents(trim(BACKEND_URL."DHTRequestHandler?act=1&key=" . urlencode($key))));
			if($id)
				$search = true;
		}
		require __DIR__.'/find.html.php';
	}
	private /*void*/ function publish()
	{
		if(isset($_SESSION['MyTransport_notice']))
		{
			echo '<div class="notice">'.$_SESSION['MyTransport_notice'].'</div>';
			unset($_SESSION['MyTransport_notice']);
		}
		if(isset($_SESSION['MyTransport_error']))
		{
			echo '<div class="error">'.$_SESSION['MyTransport_error'].'</div>';
			unset($_SESSION['MyTransport_error']);
		}
		require __DIR__.'/publish.html.php';
	}
	public /*void*/ function contentPost()
	{
		if(isset($_GET['publish']))
		{
			$key = $_POST["from"] . $_POST["to"] . $_POST["theDate"];
			$value = $_SESSION['user']['id'];
			if(file_get_contents(trim(BACKEND_URL."DHTRequestHandler?act=0&key=" . urlencode($key) . "&value=" . urlencode($value)))!==false)
				$_SESSION['MyTransport_notice'] = 'Votre trajet a bien été enregistré';
			else
				$_SESSION['MyTransport_error'] = 'Votre trajet n\'a pas été enregistré';
		}
	}
}
?>