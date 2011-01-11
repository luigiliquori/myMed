<?php 
	if($_GET["code"] == "search"){
		$key = $_GET["from1"] . $_GET["to1"] . $_GET["when11"] . $_GET["when12"] . $_GET["when13"];
		$id = file_get_contents("http://mymed2.sophia.inria.fr:8080/services/RequestHandler?act=5&key2=" . $key);
		$description = file_get_contents("http://mymed2.sophia.inria.fr:8080/services/RequestHandler?act=5&key2=" . $key);
		$bool = true;
		$search = true;	
	} else if ($_GET["code"] == "back") {
		$bool = true;
	} 
?>

<div id="myTransportSplash" class="application" style="position:absolute; top:170px; left:230px; text-align: center; color: white; 	display: none;">
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
	<h1>myTransort is starting please wait...</h1>
</div>

<div id="myTransport" class="application" style="position:absolute; top:170px; left:230px; text-align: left; display:<?= $bool ? "block" : "none" ?>">
	
	<!-- SEARCH -->
	<div id="myTransportContainer1" class="appContainer" style="background-image: url('img/map.png');">
		<form action="">
			<input type="text" style="width: 610px;"/> <input type="submit" value="Search" disabled/>
		</form>
		<div style="position: relative; width: 700px; height: 170px; <?= !$search ? "top: 300px;" : "" ?> background-color: #415b68; opacity:0.8; color: white;">
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
				<div style="background-color: #415b68; opacity:0.8; color: white; height:258px; width: 700px; overflow: auto;">
						<img style="position: absolute;" alt="" src="http://graph.facebook.com/<?= $id ?>/picture?type=large">
						<div style="position: relative; left:220px; width: 300px;">
							<? $res = json_decode(file_get_contents(trim('https://graph.facebook.com/' . $id))); ?>
							<?= $res->name ?><br />
							<?= $user->gender ?><br />
							<?= $user->locale ?><br />
							<?= $description ?><br /><br />
							<br />
							<form action="">
								<input name="code" type="hidden" value="back"/>
								<input type="submit" value="back">
							</form>
						</div>
				</div>
			<?php } ?> 
	</div>
	
	<!-- PUBLISH -->
	<div id="myTransportContainer2" class="appContainer" style="background-image: url('img/map.png'); display:none">
		<form action="">
			<input type="text" style="width: 610px;"/> <input type="submit" value="Search" disabled/>
		</form>
		<div style="position: relative; width: 700px; height: 150px; top: 300px; background-color: #415b68; opacity:0.8; color: white;">
			<form action="#">
				<input name="act" type="hidden" value="2"/>
				<table>
				    <tr>
                      <th>*When : </th>
                      <td>
                      	<input id="when21" type="text" value="jj" size="2" MAXLENGTH="2"/>/
				    	<input id="when22" type="text" value="mm" size="2" MAXLENGTH="2"/>/
				    	<input id="when23" type="text" value="aaaa" size="4" MAXLENGTH="4"/>
				   	 </td>
				   	 <td rowspan="3">
				   		 <textarea id="description" style="width:300px; height: 75px;">Short description of the trip: (number of seats, departure time, phone number, email, ...)</textarea>
				   	 </td>
                    </tr>
                    <tr>
                      <th>*From : </th>
                      <td><input id="from2" type="text" /></td>
                    </tr>
                    <tr>
                      <th>*To : </th>
                      <td><input id="to2" type="text" /></td>
                    </tr>
                    <tr>
                      <th><input type="button" onclick="submitForm('publish')" value="Publish" /></th>
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
		<img id="search" alt="" src="img/searchH.png" onclick="
			displayWindow('#myTransportContainer1'); 
			hideWindow('#myTransportContainer2'); 
			hideWindow('#myTransportContainer3');
			updateToolbar(document.getElementById('search'));"/>
		<img id="save" alt="" src="img/save.png" onclick="
			hideWindow('#myTransportContainer1'); 
			displayWindow('#myTransportContainer2'); 
			hideWindow('#myTransportContainer3');
			updateToolbar(document.getElementById('save'));"/>
		<img id="trip" alt="" src="img/trip.png" onclick="
			hideWindow('#myTransportContainer1'); 
			hideWindow('#myTransportContainer2'); 
			displayWindow('#myTransportContainer3');
			updateToolbar(document.getElementById('trip'));"/>
		<img id="trip" alt="" src="img/close.png" onclick="
			hideWindow('#myTransport');" style="position: relative; margin-left: 310px;"/>
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


