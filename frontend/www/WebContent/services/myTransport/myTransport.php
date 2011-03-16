<?php 
	$running = false;
	if($_GET["code"] == "search"){
		$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
		$id = file_get_contents(trim("http://" . $_SERVER['HTTP_HOST'] . ":8080/mymed_backend/RequestHandler?act=21&key=" . urlencode($key)));
		$running = true;
		$search = true;	
	} else if($_GET["code"] == "publish"){
		$key = $_GET["from"] . $_GET["to"] . $_GET["theDate"];
		$value = $user->id;
		file_get_contents(trim("http://" . $_SERVER['HTTP_HOST'] . ":8080/mymed_backend/RequestHandler?act=20&key=" . urlencode($key) . "&value=" . urlencode($value)));
		$running = true;
	} else if ($_GET["code"] == "back") {
		$running = true;
	} 
?>

<!-- SPLASHSCREEN -->
<div id="myTransportSplash" class="application" style="position:absolute; top:30px; left:230px; text-align: center; color: white; 	display: none;">
	<h1>myTransport v1.0</h1>
	<div style="position: relative; text-align: left; left: 20px;">
		myTransport is a multimodal transport service that you can share with your friends. <br /><br />
		What you can do with the myTransport application ?
		<ul>
		  <li> Use the graphical tool to select your trip </li>
		  <li> Share your daily trip with your firends </li>
		  <li> Select your prefered modality of transport (carSharing, bus, train, ...) </li>
		  <li> Use the mymed reputation system to find the best way for you </li>
		</ul>
	</div>
	<img alt="" src="img/myTransportScreenShot.png" width="500" />
	<h1>myTransport is starting please wait...</h1>
</div>

<!-- APPLICATION -->
<div id="myTransport" class="application" style="position:absolute; top:30px; left:230px; text-align: left; display:<?= $running ? "block" : "none" ?>">

	<!-- Setup the current date -->
   	<script type="text/javascript">
   	//<![CDATA[
		var currentTime = new Date()
		var month = currentTime.getMonth() + 1
		month = month < 10 ? "0" + month : month
		var day = currentTime.getDate()
		day = day < 10 ? "0" + day : day
		var year = currentTime.getFullYear()
	//]]>
	</script>

	<!-- SEARCH -->
	<div id="myTransportContainer1" class="appContainer" >
		<div style="position: relative; width: 700px; height: 90px; background-color: #415b68; color: white;">
			<form id="searchTrip" method="get" action="#">
				<input name="code" type="hidden" value="search"/>
				<h2>Trouvez votre covoiturage</h2>
				<table>
				  <tr>
				    <th>Ville de départ :</th><th>Ville d'arrivée :</th><th>Date :</th>
				  </tr>
				  <tr>
				    <td><input id="from1" name="from" type="text" /></td>
				    <td><input id="to1" name="to" type="text" /></td>
				    <td>
				    	<input id="theDate1" type="text" value="2011/03/09 12:55" readonly="readonly" name="theDate" /><input type="button" value="?" onclick="displayCalendar(document.getElementById('searchTrip').theDate,'yyyy/mm/dd hh:ii',this,true)" />
				    	<script type="text/javascript">
							document.getElementById("theDate1").value = year + "/" + month + "/" + day + " 12:55";
						</script>
				    </td>
				    <th><input type="submit" value="Rechercher" /></th>
				  </tr>
				</table>
			</form>
			</div>
			
			<!-- RESULT -->
			<?php if($search) { ?>
				<? $res = json_decode(file_get_contents(trim('http://mymed2.sophia.inria.fr:8080/mymed_backend/RequestHandler?act=11&id=' . $id))); ?>
				<hr />
				<form action="">
				 	<div><span style="font-size: 18px;">Results :</span>
					 	<span style="position: relative; left: 500px;">
							<input name="code" type="hidden" value="back" />
							<input type="submit" value="afficher la carte" />
						</span>
					</div>
				</form>
				<div style="background-color: #415b68; color: white; height:360px; width: 700px; overflow: auto;">
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
				</div>
			<?php } ?> 
	</div>
	
	<!-- PUBLISH -->
	<div id="myTransportContainer2" class="appContainer" style="display:none">
		<div style="position: relative; width: 700px; height: 90px; background-color: #415b68; color: white;">
			<form id="publishTrip" method="get" action="#">
				<input name="code" type="hidden" value="publish"/>
				<h2>Enregistrez votre covoiturage</h2>
				<table>
				  <tr>
				    <th>Ville de départ :</th><th>Ville d'arrivée :</th><th>Date :</th>
				  </tr>
				  <tr>
				    <td><input id="from2" name="from" type="text" /></td>
				    <td><input id="to2" name="to" type="text" /></td>
				    <td>
				    	<input id="theDate2" type="text" value="2011/03/09 12:55" readonly="readonly" name="theDate" /><input type="button" value="?" onclick="displayCalendar(document.getElementById('publishTrip').theDate,'yyyy/mm/dd hh:ii',this,true)" />
				    	<script type="text/javascript">
							document.getElementById("theDate2").value = year + "/" + month + "/" + day + " 12:55";
						</script>
				    </td>
				    <th><input type="submit" value="Publiez" /></th>
				  </tr>
				</table>
			</form>
		</div>
	</div>
	
	<!-- Google map -->
	<div id="mapcanvas" style="width: 700px; height: 360px;"></div>
	<script type="text/javascript" src="services/myTransport/javascript/jquery.autocomplete_geomod.js"></script>
	<script type="text/javascript" src="services/myTransport/javascript/geo_autocomplete.js"></script>
	<script type="text/javascript" src="services/myTransport/javascript/map.js"></script>
	<link rel="stylesheet" type="text/css" href="services/myTransport/css/jquery.autocomplete.css" />
	
	<?php if($running && $_GET["code"] != "search") { ?>
		<script type="text/javascript">launchGeolocation();</script>
	<?php } else { ?>
		<form action="">
		 	<div><span style="font-size: 18px;">Results :</span>
			 	<span style="position: relative; left: 500px;">
					<input name="code" type="hidden" value="back" />
					<input type="submit" value="afficher la carte" />
				</span>
			</div>
		</form>
	<?php } ?>
	
	<!-- Application footer -->
	<div class="appToolbar">
		<table>
		  <tr>
		    <td>
			    <img id="search" alt="" src="img/searchH.png" onclick="
			    document.getElementById('myTransportContainer2').style.display = 'none';
				fadeIn('#myTransportContainer1'); 
				updateToolbar(document.getElementById('search'));" />
			</td>
			<td>
				<img id="save" alt="" src="img/save.png" onclick="
					 document.getElementById('myTransportContainer1').style.display = 'none';
				fadeIn('#myTransportContainer2'); 
				updateToolbar(document.getElementById('save'));" />
			</td>
			<td style="width: 400px;"></td>
			<td>
				<form method="get" action="#">
					<input type="submit" style="background-image: url('img/close.png'); width: 100px; height: 48px;" value=" " onclick="location.reload();" />
				</form>
			</td>
		  </tr>
		</table>
		<script type="text/javascript">
			function updateToolbar(elm){
				document.getElementById('search').src = 'img/search.png';
				document.getElementById('save').src = 'img/save.png';
				elm.src = 'img/'+elm.id+'H.png';
			}
		</script>
	</div>
</div>


