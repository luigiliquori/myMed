<? include("header.php"); ?>

<div data-role="page" id="users">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_simple('Admin',  $_SESSION['user']->name) ?>
		<? tab_bar_white("#users") ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content">
	
		
		
		<a type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="alert" style="float: right;"
		onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:users", "<?= APPLICATION_NAME ?>:users", []); $(this).addClass("ui-disabled");'><?= _("Subscribe") ?></a>
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		
		<p><?= _("New users waiting for validation") ?>: <?= count($this->blocked) ?></p>
		<ul data-role="listview" data-inset="true">
		<? foreach( $this->blocked as $i => $item ) : ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>">
					<input type="hidden" name="perm" value="<?= $item->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>">
					<input type="hidden" name="perm" value="<?= $item->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				
				<a href="?action=ExtendedProfile&id=<?= $item->profile ?>"> <?= $item->email ?></a>
				<a rel="external" data-icon="minus" href="?action=Admin&id=<?= $item->id ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="plus" href="?action=Admin&id=<?= $item->id ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a> 

			</li>
		<? endforeach ?>
		</ul>
		
		<p><?= _("Normal users") ?>: <?= count($this->normals) ?></p>
		<ul data-role="listview" data-inset="true">
		<? foreach( $this->normals as $i => $item ) : ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>">
					<input type="hidden" name="perm" value="<?= $item->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>">
					<input type="hidden" name="perm" value="<?= $item->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
					
				</form>
				
				<a href="?action=ExtendedProfile&id=<?= $item->profile ?>"> <?= $item->email ?></span></a>
				<a rel="external" data-icon="minus" href="?action=Admin&id=<?= $item->id ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="plus" href="?action=Admin&id=<?= $item->id ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a> 
				
			</li>
		<? endforeach ?>	
		</ul>
		
		<p><?= _("Admins") ?>: <?= count($this->admins) ?></p>
		<ul data-role="listview" data-inset="true">
		<? foreach( $this->admins as $i => $item ) : ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>">
					<input type="hidden" name="perm" value="<?= $item->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>">
					<input type="hidden" name="perm" value="<?= $item->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
					
				</form>
				
				<a href="?action=ExtendedProfile&id=<?= $item->profile ?>"> <?= $item->email ?></span></a>
				<a rel="external" data-icon="minus" href="?action=Admin&id=<?= $item->id ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="plus" href="?action=Admin&id=<?= $item->id ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a> 
				

			</li>
		<? endforeach ?>	
		</ul>
		
	</div>

</div>



<? include("footer.php"); ?>