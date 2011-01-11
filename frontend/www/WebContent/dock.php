
<!-- DOCK BACKGROUND -->
<div style="position: absolute; left: 230px; top:200px; background-image: url('img/dock.png'); width: 700px; height: 70px;" ></div>

<!-- DOCK APPLICATIONS -->
<table cellspacing="0" style="position: absolute; left: 295px; top:180px;">
	<tr>
	<td>
		<div id="app1" style="background-image: url('img/icon/myTransport.png'); width: 80px; height: 100px; text-align: center;" onmouseover="activeDock('app1')" >
			<span style="position:relative; top:60px;">myTransport</span>
		</div>
		<div id="app1H" style="position: relative; background-image: url('img/icon/myTransportH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;"" onclick="launchApplication('myTransport', true);" onmouseout="desactiveDock('app1')">
			<span style="position:relative; top:80px;">myTransport</span>
		</div>
	</td>
	<td>
		<div id="app2" class="drag" style="background-image: url('img/icon/myMontagneD.png'); width: 80px; height: 100px; text-align: center;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:60px;">myMountain</span>
		</div>
	</td>
	<td>
		<div id="app3" class="drag" style="background-image: url('img/icon/myAngelD.png'); width: 80px; height: 100px; text-align: center;"onclick="displayWindow('#warning')" >
			<span style="position:relative; top:60px;">myAngel</span>
		</div>
	</td>
	<td>
		<div id="app4" class="drag" style="background-image: url('img/icon/myProductD.png'); width: 80px; height: 100px; text-align: center;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:60px;">myProduct</span>
		</div>
	</td>
	<td>
		<div id="app5" class="drag" style="background-image: url('img/icon/myCasounD.png'); width: 80px; height: 100px; text-align: center;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:60px;">myCasoun</span>
		</div>
	</td>
	<td>
		<div id="app6" class="drag" style="background-image: url('img/icon/myKayakD.png'); width: 80px; height: 100px; text-align: center;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:60px;">myKayak</span>
		</div>
	</td>
	<td>
		<div id="app7" class="drag" style="background-image: url('img/icon/myInfoD.png'); width: 80px; height: 100px; top:-10px;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:70px;">myInfo</span>
		</div>
	</td>
	</tr>
</table>