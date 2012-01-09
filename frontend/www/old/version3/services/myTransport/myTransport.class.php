<?php
require_once __DIR__.'/../../system/backend/DHTRequest.class.php';
require_once __DIR__.'/../../system/backend/ProfileRequest.class.php';
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
	public /*string*/ function getTitle(){return 'myTransport';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<link rel="stylesheet" type="text/css" href="<?=ROOTPATH?>/services/myTransport/jquery.autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="<?=ROOTPATH?>/services/myTransport/style.css" />
		<style type="text/css">
		#myTransport {
			background-image: url('<?=ROOTPATH?>services/myTransport/background.png');
		}
		</style>
		<script type="text/javascript" src="<?=ROOTPATH?>/services/myTransport/jquery.autocomplete_geomod.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>/services/myTransport/geo_autocomplete.js"></script>
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
		global $PATH_INFO;
		$serviceName	= __CLASS__;
		if(!isset($PATH_INFO[2]))
		{
			if(isset($_GET["from"], $_GET["to"], $_GET["theDate"]))
			{
				$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
				try
				{
					$profileRequest	= new ProfileRequest;
					$res	= $profileRequest->read($this->request->read($key));
				}
				catch(BackendRequestException $ex)
				{
					if($ex->getCode() == 404)
						$res = null;
					else
						throw $ex;
				}
			}
			require __DIR__.'/views/search.view.php';
		}
		else if($PATH_INFO[2] === "publish")
			require __DIR__.'/views/publish.view.php';
		else
			header("Status: 404 Not Found", false, 404);
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost()
	{
		global $PATH_INFO;
		if(isset($PATH_INFO[2])&&($PATH_INFO[2] === "publish")){
			$key = $_POST["from"] . $_POST["to"] . $_POST["theDate"];
			$value = $_SESSION['user']->id;
			$this->request->create($key, $value);
			$_SESSION['myTransport_publish_info']	= '<p>Trajet publié !</p>';
		}
	}
}
?>
