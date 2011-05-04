<div id="tab3" style="display: none;">
	<table style="position:absolute; top:152px; left: 0px;">
	  <tr>
	    <td><div class="tab" style="width: 146px; background-image: url('img/tab/tabOff.png');"><a href="#" style="position:relative; top:5px;" onclick="cleanView(); fadeIn('#tab1');">Travail</a></div></td>
	    <td><div class="tab" style="width: 146px;background-image: url('img/tab/tabOff.png');"><a href="#" style="position:relative; top:3px;" onclick="cleanView(); fadeIn('#tab2');">Pratique</a></div></td>
	    <td><div class="tab" style="width: 148px; background-image: url('img/tab/tabOn.png');"><a href="#" style="position:relative; top:3px;" onclick="cleanView(); fadeIn('#tab3');">Fun</a></div></td>
	    <td><div class="tab" style="width: 32px; background-image: url('img/tab/plus.png');"></div></td>
	  </tr>
	</table>
	
	<table style="position:absolute; top:200px; left: 20px;">
	  <tr>
	    <td><?php include('app/myBigApp.php'); ?></td>
	    <td><?php include('app/myBigApp.php'); ?></td>
	  </tr>
	  <tr>
	    <td><?php include('app/mySmallApp.php'); ?></td>
	  </tr>
	</table>
</div>

