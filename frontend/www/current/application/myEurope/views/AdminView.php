<? include("header.php"); ?>

<div data-role="page" id="users">

	<? tabs_simple(array('Admin')); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content">
		
		<a type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign" style="float: right;"
		onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:users", "<?= APPLICATION_NAME ?>:users", []); $(this).addClass("ui-disabled");'><?= "Subscribe" ?></a>
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		<br />
		<ul data-role="listview" data-inset="true" data-filter="true" >
			<li data-role="list-divider"><?= _("New users waiting for validation") ?><span class="ui-li-count"><?= count($this->blocked) ?></span></li>
		<? foreach( $this->blocked as $i => $item ) : ?>
			<li>		
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"> <?= $item->email ?></a>
				<a rel="external" data-icon="chevron-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="chevron-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
			<li data-role="list-divider"><?= _("Normal users") ?><span class="ui-li-count"><?= count($this->normals) ?></span></li>
		<? foreach( $this->normals as $i => $item ) : ?>
			
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"> <?= $item->email ?></a>
				<a rel="external" data-icon="chevron-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="chevron-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
			<li data-role="list-divider"><?= _("Admins") ?><span class="ui-li-count"><?= count($this->admins) ?></span></li>
			<? foreach( $this->admins as $i => $item ) : ?>
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"> <?= $item->email ?></a>
				<a rel="external" data-icon="chevron-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="chevron-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
		</ul>
		
	</div>

</div>



<? include("footer.php"); ?>