<?php
class myTransport extends ContentObject
{
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
			$id = file_get_contents(trim(BACKEND_URL.'DHTRequestHandler?act=1&key=' . urlencode($key)));
		} else if( isset($_GET["code"])&&($_GET["code"] == "publish") ){
			$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
			$value = $_SESSION['user']['id'];
			file_get_contents(trim(BACKEND_URL.'DHTRequestHandler?act=0&key=' . urlencode($key) . '&value=' . urlencode($value)));
		} 
		?>
		
		<!-- APPLICATION -->
		<div id="myTransport">
			
			<?php if(!isset($_GET["code"])) { ?>
			
			<!-- menu -->
			<br />
				<a href="#" onclick="document.getElementById('myTransportSub').style.display = 'none'; fadeIn('#myTransportPub')">Rechercher</a> | 
				<a href="#" onclick="document.getElementById('myTransportPub').style.display = 'none'; fadeIn('#myTransportSub')">Publier</a>
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
			
			<?php } else { ?>
			
			<!-- Result -->
			<div id="myTransportRes">
				<?php if($_GET["code"] == "search") { ?>
					<? $res = json_decode(file_get_contents(trim(BACKEND_URL.'ProfileRequestHandler?act=1&id=' . $id))); ?>
					<form action="">
					 	<div><span style="font-size: 18px;">Results :</span>
						 	<span style="position: relative; left: 280px;">
								<input type="submit" value="back" />
							</span>
						</div>
					</form>
					<table>
					  <tr rowspan="4">
					    <td><img width="200px" alt="profile picture" src="<?= $res->profile_picture ?>" /></td>
					    <td>
						    <table>
							  <tr>
							  	<td></td>
							    <td>
								    <h1><?= $res->name ?></h1>
								    <ul>
									  <li><?= $res->gender ?></li>
									  <li><?= $res->locale ?></li>
									</ul>
							   	</td>
							  </tr>
							</table>
					    </td>
					  </tr>
					</table>
				<?php } else { ?>
						<form action="">
					 	<div><span style="font-size: 18px;">Trip published!</span>
						 	<span style="position: relative; left: 220px;">
								<input type="submit" value="back" />
							</span>
						</div>
						</form>
				<?php } ?>
			</div>
			<?php } ?>
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
