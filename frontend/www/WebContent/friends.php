<!-- FRIENDS STREAM -->
<div style="background-color: #415b68; color: white; width: 200px; font-size: 15px; font-weight: bold;">my Friends</div>
<div style="position:relative; height: 250px; width: 200px; overflow: auto; background-color: #edf2f4; top:0px;">
	<?php while (list(, $value) = each($friends)) { ?>
		<a href=http://www.facebook.com/#!/profile.php?id=<?= $value->id ?>"><?= $value->name ?></a><br />
	<?php } ?>
</div>