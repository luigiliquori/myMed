<div class="appSmall">

	<div class="appTitle">myFriends</div>
	
	<div class="appContentSmall">
		<!-- FRIENDS STREAM -->
		<div>
			<?php while (list(, $value) = each($friends)) { ?>
				<a href=http://www.facebook.com/#!/profile.php?id=<?= $value->id ?>"><?= $value->name ?></a><br />
			<?php } ?>
	
		</div>
	</div>

</div>