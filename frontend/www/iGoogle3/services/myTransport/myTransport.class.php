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
	public /*string*/ function getTitle(){return 'myTransport';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<link rel="stylesheet" type="text/css" href="services/myTransport/jquery.autocomplete.css" />
		<style>
		#myTransport {
			min-height: 248px;
    		width:100%;
    		height:100%;
    		overflow:auto;
    		background-image: url('services/myTransport/background.png');
    		background-repeat		: no-repeat;
    		background-size			: 100% 100%;
    		-moz-background-size	: 100% 100%;
    		-o-background-size		: 100% 100%;
    		-khtml-background-size	: 100% 100%;
		}
		#myTransport th {
			text-align	: inherit;
		}
		</style>
		<script type="text/javascript" src="services/myTransport/jquery.autocomplete_geomod.js"></script>
		<script type="text/javascript" src="services/myTransport/geo_autocomplete.js"></script>
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

		<!-- REQUEST -->
		<?php 
		$running = false;
		if( isset($_GET["code"])&&($_GET["code"] == "search") ){
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
		} else if( isset($_GET["code"])&&($_GET["code"] == "publish") ){
			$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
			$value = $_SESSION['user']->mymedID;
			$this->request->create($key, $value);
		} 
		?>
		
		<!-- APPLICATION -->
		<div id="myTransport">
			
<?php if(!isset($_GET["code"])): ?>
			
			<!-- menu -->
			<br />
				<a href="#" onclick="document.getElementById('myTransportSub').style.display = 'none'; fadeIn('#myTransportPub')">Rechercher</a> | 
<?php 	if($_SESSION['user']->mymedID!==null):?>
				<a href="#" onclick="document.getElementById('myTransportPub').style.display = 'none'; fadeIn('#myTransportSub')">Publier</a>
<?php 	else:?>
				Publier
<?php 	endif?>
			<br /><hr />
			
			<!-- Subscribe -->
			<div id="myTransportPub" >
				<form id="searchTrip" method="get" action="#">
					<input name="code" type="hidden" value="search"/>
					<table>
					  <tr>
					    <th>Ville de départ : </th><td><input id="from1" name="from" type="text" /></td>
					  </tr>
					  <tr>
					    <th>Ville d'arrivée : </th><td><input id="to1" name="to" type="text" /></td>
					  </tr>
					  <tr>
					    <th>Date : </th><td><input style="width: 180px;" id="theDate1" name="theDate" type="date" value="<?php echo date('Y-m-d');?>" size="10" /></td>
					  </tr>	
					  <tr>
					    <td><input type="submit" value="Rechercher" /></td>
					  </tr>			  
					</table>
				</form>
			</div>
			
			<!-- Publish -->
			<div id="myTransportSub" style="display: none;">
				<form id="searchTrip" method="get" action="#">
					<input name="code" type="hidden" value="publish"/>
					<table>
					  <tr>
					    <th>Ville de départ : </th><td><input id="from2" name="from" type="text" /></td>
					  </tr>
					  <tr>
					    <th>Ville d'arrivée : </th><td><input id="to2" name="to" type="text" /></td>
					  </tr>
					  <tr>
					    <th>Date : </th><td><input style="width: 180px;" id="theDate2" name="theDate" type="date" value="<?php echo date('Y-m-d');?>" size="10" /></td>
					  </tr>	
					  <tr>
					    <th>Informations : </th><td><textarea style="width: 180px;" id="info" ></textarea></td>
					  </tr>	
					  <tr>
					    <td><input type="submit" value="Publier" /></td>
					  </tr>			  
					</table>
				</form>
			</div>
			
<?php else : ?>
			
			<!-- Result -->
			<div id="myTransportRes">
<?php 	if($_GET["code"] == "search"): ?>
					<form action="">
					 	<div><span style="font-size: 18px;">Results :</span>
						 	<span style="position: relative; float: right;">
								<input type="submit" value="back" />
							</span>
						</div>
					</form>
<?php 		if($res!=null):?>
					<table>
					  <tr rowspan="4">
					    <td><img width="200px" alt="profile picture" src="<?= $res->profilePicture ?>" /></td>
					    <td>
						    <table>
							  <tr>
							  	<td></td>
							    <td>
								    <h1><?= $res->name ?></h1>
								    <ul>
										<li><?= $res->gender=='F'?'Femme':'Homme' ?></li>
										<li><?= $res->birthday ?></li>
									</ul>
							   	</td>
							  </tr>
							</table>
					    </td>
					  </tr>
					</table>
<?php 		else: ?>
				Introuvable
<?php 		endif?>
<?php 	else: ?>
						<form action="">
					 	<div><span style="font-size: 18px;">Trip published!</span>
						 	<span style="position: relative; float: right;">
								<input type="submit" value="back" />
							</span>
						</div>
						</form>
<?php 	endif?>
			</div>
<?php endif?>
		</div>
		<script type="text/javascript">$(":date").dateinput({lang:'fr', format:'yyyy-mm-dd', firstDay:1/*, selectors:true*/});</script>
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
