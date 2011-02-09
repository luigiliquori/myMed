<?php 
	$running = false;
	if($_GET["code"] == "search"){
		$key = $_GET["from1"] . $_GET["to1"] . $_GET["when11"] . $_GET["when12"] . $_GET["when13"];
		$id = file_get_contents(trim("http://mymed2.sophia.inria.fr:8080/mymed_backend/FirstRequestHandler?act=5&key2=" . $key));
		$running = true;
		$search = true;	
	} else if($_GET["code"] == "publish"){
		$key = $_GET["from2"] . $_GET["to2"] . $_GET["when21"] . $_GET["when22"] . $_GET["when23"];
		$value = $user->id;
		file_get_contents(trim("http://mymed2.sophia.inria.fr:8080/mymed_backend/FirstRequestHandler?act=4&key1=" . $key . "&value1=" . $value));
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
	<img alt="" src="img/myTransportScreenShot.png" width="500">
	<h1>myTransport is starting please wait...</h1>
</div>

<!-- APPLICATION -->
<div id="myTransport" class="application" style="position:absolute; top:30px; left:230px; text-align: left; display:<?= $running ? "block" : "none" ?>">
	
	<!-- SEARCH -->
	<div id="myTransportContainer1" class="appContainer" style="background-image: url('img/map.png');">
		<div style="position: relative; width: 700px; height: 170px; <?= !$search ? "top: 340px;" : "" ?> background-color: #415b68; opacity:0.8; color: white;">
			<form method="get" action="#">
				<input name="code" type="hidden" value="search"/>
				<table>
				 <tr>
				    <th>*When : </th>
				    <td>
				    	<input name="when11" type="text" value="jj" size="2" MAXLENGTH="2"/>/
				    	<input name="when12" type="text" value="mm" size="2" MAXLENGTH="2"/>/
				    	<input name="when13" type="text" value="aaaa" size="4" MAXLENGTH="4"/>
				    </td>
				  </tr>
				  <tr>
				    <th>*From : </th>
				    <td><input name="from1" type="text" /></td>
				  </tr>
				  <tr>
				    <th>*To : </th>
				    <td><input name="to1" type="text" /></td>
				  </tr>
				  <tr>
				    <th><input type="submit" value="Search" /></th>
				  </tr>
				</table>
			</form>
			<?php if($search) { ?>
				<hr />
				<h1>Results :</h1>
			<?php } ?>
			</div>
			
			<!-- RESULT -->
			<?php if($search) { ?>
				<? $res = json_decode(file_get_contents(trim('http://mymed2.sophia.inria.fr:8080/mymed_backend/RequestHandler?act=11&id=' . $id))); ?>
				<div style="background-color: #415b68; opacity:0.8; color: white; height:298px; width: 700px; overflow: auto;">
						<table>
						  <tr rowspan="4">
						    <td><img width="200px" alt="profile picture" src="<?= $res->profile_picture ?>"></td>
						    <td>
							    <table>
								  <tr>
								  	<td></td>
								    <td><?= $res->name ?></td>
								  </tr>
								  <tr>
								  	<td></td>
								    <td><?= $res->gender ?></td>
								  </tr>
								  <tr>
								  	<td></td>
								    <td><?= $res->locale ?></td>
								  </tr>
								  <tr>
								  	<td></td>
								    <td>
									    <form action="">
											<input name="code" type="hidden" value="back"/>
											<input type="submit" value="back">
										</form>
									</td>
								  </tr>
								</table>
						    </td>
						</table>
				</div>
			<?php } ?> 
	</div>
	
	<!-- PUBLISH -->
	<div id="myTransportContainer2" class="appContainer" style="background-image: url('img/map.png'); display:none">
		<div style="position: relative; width: 700px; height: 150px; top: 340px; background-color: #415b68; opacity:0.8; color: white;">
			<form method="get" action="#">
				<input name="code" type="hidden" value="publish"/>
				<table>
				    <tr>
                      <th>*When : </th>
                      <td>
                      	<input name="when21" type="text" value="jj" size="2" MAXLENGTH="2"/>/
				    	<input name="when22" type="text" value="mm" size="2" MAXLENGTH="2"/>/
				    	<input name="when23" type="text" value="aaaa" size="4" MAXLENGTH="4"/>
				   	 </td>
				   	 <td rowspan="3">
				   		 <textarea name="description" style="width:300px; height: 75px;">Short description of the trip: (number of seats, departure time, phone number, email, ...)</textarea>
				   	 </td>
                    </tr>
                    <tr>
                      <th>*From : </th>
                      <td><input name="from2" type="text" /></td>
                    </tr>
                    <tr>
                      <th>*To : </th>
                      <td><input name="to2" type="text" /></td>
                    </tr>
                    <tr>
                      <th><input type="submit" value="Publish" /></th>
                    </tr>
				</table>
			</form>
		</div>
	</div>
	
	<!-- ??? -->
	<div id="myTransportContainer3" class="appContainer" style="display: none;">
		<br />
		Not yet implemented...
	</div>
	
	<div class="appToolbar">
		<table>
		  <tr>
		    <td>
			    <img id="search" alt="" src="img/searchH.png" onclick="
				displayWindow('#myTransportContainer1'); 
				hideWindow('#myTransportContainer2'); 
				hideWindow('#myTransportContainer3');
				updateToolbar(document.getElementById('search'));"/>
			</td>
			<td>
				<img id="save" alt="" src="img/save.png" onclick="
				hideWindow('#myTransportContainer1'); 
				displayWindow('#myTransportContainer2'); 
				hideWindow('#myTransportContainer3');
				updateToolbar(document.getElementById('save'));"/>
			</td>
			<td>
				<img id="trip" alt="" src="img/trip.png" onclick="
				hideWindow('#myTransportContainer1'); 
				hideWindow('#myTransportContainer2'); 
				displayWindow('#myTransportContainer3');
				updateToolbar(document.getElementById('trip'));"/>
			</td>
			<td style="width: 300px;"></td>
			<td>
				<form method="get" action="#">
					<input type="submit" style="background-image: url('img/close.png'); width: 100px; height: 48px;" value=" " onclick="location.reload();">
				</form>
			</td>
		  </tr>
		</table>
		<script type="text/javascript">
			function updateToolbar(elm){
				document.getElementById('search').src = 'img/search.png';
				document.getElementById('save').src = 'img/save.png';
				document.getElementById('trip').src = 'img/trip.png';
				elm.src = 'img/'+elm.id+'H.png';
			}
		</script>
	</div>
</div>


