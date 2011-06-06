<?php if(!@$search):?>
	<!-- Google map -->
	<div id="mapcanvas" style="width: 100%; height: 360px;"></div>
	<!--
		//for compatibility with XHTML don't use http://maps.google.com/maps/api/js?sensor=false 
		//but http://maps.google.com/maps/api/js?sensor=false&callback=launchGeolocation
		//it will call launchGeolocation()
	-->
	<!--script type="text/javascript">launchGeolocation();</script-->
<?php endif?>
	<!-- Application footer -->
	<div class="appToolbar">
		<table>
		  <tr>
		    <td>
				<a href="?service=myTransport">
<?php if(isset($_GET['publish'])&&$_SESSION['user']->mymedID!==null):?>
					<img alt="search" src="services/myTransport/img/search.png" style="border:0 none" />
<?php else:?>
					<img alt="search" src="services/myTransport/img/searchH.png" style="border:0 none" />
<?php endif?>
				</a>
			</td>
			<td>
<?php if($_SESSION['user']->mymedID!==null):?>
				<a href="?service=myTransport&amp;publish=">
					
<?php 	if(isset($_GET['publish'])):?>
					<img alt="publish" src="services/myTransport/img/saveH.png" style="border:0 none" />
<?php 	else:?>
					<img alt="publish" src="services/myTransport/img/save.png" style="border:0 none" />
<?php 	endif?>
				</a>
<?php else:?>
					<img alt="publish" src="services/myTransport/img/save_disable.png" />
<?php endif?>
			</td>
			<td style="width: 400px;"></td>
			<td>
				<a style="background-image: url('services/myTransport/img/close.png'); width: 100px; height: 48px; display:inline-block;" href="?">
				</a>
			</td>
		  </tr>
		</table>
	</div>