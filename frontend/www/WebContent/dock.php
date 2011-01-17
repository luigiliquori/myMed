
<!-- DOCK BACKGROUND -->
<div id="dock" style="position: absolute; left: 230px; top:600px; background-image: url('img/dock.png'); width: 700px; height: 70px;" ></div>

<!-- DOCK APPLICATIONS -->
<table cellspacing="0" style="position: absolute; left: 295px; top:580px;">
	<tr>
	<td>
		<div id="app1" style="background-image: url('img/icon/myHome.png'); width: 80px; height: 100px; text-align: center;" onmouseover="activeDock('app1')" >
			<span style="position:relative; top:60px;">myProfile</span>
		</div>
		<a href="http://www.facebook.com/profile.php?id=<?= $user->id ?>" id="app1H" style="position: relative; background-image: url('img/icon/myHomeH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;"" onmouseout="desactiveDock('app1')">
			<span style="position:relative; top:80px;">myProfile</span>
		</a>
	</td>
	<td>
		<div id="app3" style="background-image: url('img/icon/myStore.png'); width: 80px; height: 100px; text-align: center;" onmouseover="activeDock('app3')" >
			<span style="position:relative; top:60px;">myStore</span>
		</div>
		<div id="app3H" style="position: relative; background-image: url('img/icon/myStoreH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;" onclick="fadeIn('#store')" onmouseout="desactiveDock('app3')">
			<span style="position:relative; top:80px;">myStore</span>
		</div>
	</td>
	<td>
		<div id="app4" style="background-image: url('img/icon/myPref.png'); width: 80px; height: 100px; text-align: center;" onmouseover="activeDock('app4')" >
			<span style="position:relative; top:60px;">myPreference</span>
		</div>
		<div id="app4H" style="position: relative; background-image: url('img/icon/myPrefH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;" onclick="displayWindow('#warning')" onmouseout="desactiveDock('app4')">
			<span style="position:relative; top:80px;">myPreference</span>
		</div>
	</td>
	<td>
		<div id="app2" style="background-image: url('img/icon/myTransport.png'); width: 80px; height: 100px; text-align: center;" onmouseover="activeDock('app2')" >
			<span style="position:relative; top:60px;">myTransport</span>
		</div>
		<div id="app2H" style="position: relative; background-image: url('img/icon/myTransportH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;" onclick="launchApplication('myTransport', true);" onmouseout="desactiveDock('app2')">
			<span style="position:relative; top:80px;">myTransport</span>
		</div>
	</td>
	<td>
		<div id="app5" class="drag" style="background-image: url('img/icon/myAngelD.png'); width: 80px; height: 100px; text-align: center;"onclick="displayWindow('#warning')" >
			<span style="position:relative; top:60px;">myAngel</span>
		</div>
	</td>
	<td>
		<div id="app6" class="drag" style="background-image: url('img/icon/myProductD.png'); width: 80px; height: 100px; text-align: center;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:60px;">myProduct</span>
		</div>
	</td>
	<td>
		<div id="app7" class="drag" style="background-image: url('img/icon/myInfoD.png'); width: 80px; height: 100px; top:-10px;" onclick="displayWindow('#warning')">
			<span style="position:relative; top:70px;">myInfo</span>
		</div>
	</td>
	</tr>
</table>