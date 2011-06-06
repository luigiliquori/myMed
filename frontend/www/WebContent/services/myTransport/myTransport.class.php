<?php
require_once dirname(__FILE__).'/../../system/backend/DHTRequest.class.php';
require_once dirname(__FILE__).'/../../system/backend/ProfileRequest.class.php';
class myTransport extends ContentObject
{
	private /*DHTRequest*/ $request;
	public function __construct()
	{
		$this->request	= new DHTRequest;
	}
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle()
	{
		return 'myTransport';
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		//for compatibility with XHTML don't use http://maps.google.com/maps/api/js?sensor=false 
		//but http://maps.google.com/maps/api/js?sensor=false&callback=launchGeolocation
?>
		<link rel="stylesheet" href="services/myTransport/css/jquery.autocomplete.css" />
		<style type="text/css">
		#myTransport{height: 100%;}
		#myTransport div.error ,
		#myTransport div.notice {
			position	: absolute;
			width		: 100%;
			text-align	: center;
		}
		#myTransport div.notice span ,
		#myTransport div.error span {
			padding	: 0.5ex;
			border	: 1px solid #D2D2D2;
			display	: inline-block;
		}
		#myTransport div.notice span {
			background-color	: #EDF2F4;
		}
		#myTransport div.error span {
			background-color	: #ff0000;
		}
		</style>
		<script src="services/myTransport/javascript/map.js"></script>
		<script src="services/myTransport/javascript/geo_autocomplete.js"></script>
		<script src="services/myTransport/javascript/jquery.autocomplete_geomod.js"></script>
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
		require dirname(__FILE__).'/footer.html.php';
		echo '</div>';
	}
	private /*void*/ function find()
	{
		if(isset($_GET["search"]))
		{
			$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
			try
			{
				$id	= $this->request->read($key);
				$profileRequest	= new ProfileRequest;
				$res	= $profileRequest->read($id);
				$found = true;
			}
			catch(BackendRequestException $ex)
			{
				if($ex->getCode() == 404)
					$found = false;
				else
					throw $ex;
			}
		}
		require dirname(__FILE__).'/find.html.php';
	}
	private /*void*/ function publish()
	{
		if(isset($_SESSION['MyTransport_notice']))
		{
			echo '<div class="notice"><span>'.$_SESSION['MyTransport_notice'].'</span></div>';
			unset($_SESSION['MyTransport_notice']);
		}
		if(isset($_SESSION['MyTransport_error']))
		{
			echo '<div class="error"><span>'.$_SESSION['MyTransport_error'].'</span></div>';
			unset($_SESSION['MyTransport_error']);
		}
		require dirname(__FILE__).'/publish.html.php';
	}
	public /*void*/ function contentPost()
	{
		if(isset($_GET['publish']))
		{
			$key = $_POST["from"] . $_POST["to"] . $_POST["theDate"];
			$value = $_SESSION['user']->mymedID;
			try
			{
				$this->request->create($key, $value);
				$_SESSION['MyTransport_notice'] = 'Votre trajet a bien été enregistré';
			}
			catch(BackendRequestException $ex)
			{
				$_SESSION['MyTransport_error'] = 'Votre trajet n\'a pas été enregistré :<br />'.$ex->getCode().': '.$ex->getMessage();
			}
		}
	}
}
?>