<!-- HOME -->
<div class="drag" style="position:absolute; top:50px; background-image: url('img/icon/home.png'); width: 100px; height: 100px;">
	<div style="position: absolute; top:60px; text-align: center; width: 100%;">
		<a href="<?= $user->link ?>">myProfile</a>
	</div>
</div>

<!-- MYTRANSPORT -->
<div class="drag" style="position:absolute; top:150px; left:15px; background-image: url('img/icon/myTransport.png'); width: 80px; height: 100px;" ondblclick="launchApplication('myTransport', true);">
	<div style="position: absolute; top:60px; text-align: center; width: 100%;">
		<a href="#" onclick="launchApplication('myTransport', true);">myTransport</a>
	</div>
</div>