<!-- DOCK BACKGROUND -->
<div id="dock" style="position: absolute; top:450px; left:100px; background-image: url('img/dock.png'); width:446px; height: 70px;" ></div>

<!-- DOCK APPLICATIONS -->
<table cellspacing="0" style="position: absolute; top:430px; left:160px;">
	<tr>
	<td>
		<div id="app1" style="background-image: url('img/icon/myHome.png'); width: 80px; height: 100px; text-align: center;" onmouseover="activeDock('app1')" >
			<span style="position:relative; top:60px;">myProfile</span>
		</div>
		<a href="<?= $user->profile ?>" id="app1H" style="position: relative; background-image: url('img/icon/myHomeH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;" onmouseout="desactiveDock('app1')">
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
		<div id="app7" style="position: relative; background-image: url('img/icon/myInfo.png'); width: 80px; height: 100px; top:-10px;  text-align: center;" onmouseover="activeDock('app7')">
			<span style="position:relative; top:70px;">myInfo</span>
		</div>
		<div id="app7H" style="position: relative; background-image: url('img/icon/myInfoH.png'); width: 80px; height: 100px; left: 0px; top:-20px; display: none; text-align: center;" onclick="displayWindow('#warning')" onmouseout="desactiveDock('app7')" >
			<span style="position:relative; top:80px;">myInfo</span>
		</div>
	</td>
	</tr>
</table>