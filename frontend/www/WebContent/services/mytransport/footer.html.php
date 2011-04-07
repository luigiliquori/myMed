<?php if(!@$search){?>
	<!-- Google map -->
	<div id="mapcanvas" style="width: 100%; height: 360px;"></div>
	<!--
		//for compatibility with XHTML don't use http://maps.google.com/maps/api/js?sensor=false 
		//but http://maps.google.com/maps/api/js?sensor=false&callback=launchGeolocation
		//it will call launchGeolocation()
	-->
	<!--script type="text/javascript">launchGeolocation();</script-->
<?php }?>
	<!-- Application footer -->
	<div class="appToolbar">
		<table>
		  <tr>
		    <td>
				<a href="?service=MyTransport">
				<?php if(isset($_GET['publish'])){?>
					<img alt="search" src="services/mytransport/img/search.png" style="border:0 none" />
					<?php }else{?>
					<img alt="search" src="services/mytransport/img/searchH.png" style="border:0 none" />
					<?php }?>
				</a>
			</td>
			<td>
				<a href="?service=MyTransport&amp;publish=">
					<?php if(isset($_GET['publish'])){?>
					<img alt="publish" src="services/mytransport/img/saveH.png" style="border:0 none" />
					<?php }else{?>
					<img alt="publish" src="services/mytransport/img/save.png" style="border:0 none" />
					<?php }?>
				</a>
			</td>
			<td style="width: 400px;"></td>
			<td>
				<a style="background-image: url('services/mytransport/img/close.png'); width: 100px; height: 48px; display:inline-block;" href="?">
				</a>
			</td>
		  </tr>
		</table>
	</div>