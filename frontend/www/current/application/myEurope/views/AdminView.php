<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="users">
	<? print_header_bar(true, "defaultHelpPopup"); ?>
	
	<div data-role="content">
		<br>
		<a type="button" href="?action=ExtendedProfile&list" data-mini="true" data-inline="true" rel="external"  data-icon="list" title="<?= _('list of organizations profiles in myEurope') ?>"><?= _('Profiles list') ?></a>
				
		<a type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign" style="float: right;"
		onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:users", "<?= APPLICATION_NAME ?>:users", []); $(this).addClass("ui-disabled");'><?= _("Notify me of new users") ?></a>
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		<br /><br />
		<ul data-role="listview" data-filter="true" >
			<li data-role="list-divider"><?= _("New users waiting for validation") ?><span class="ui-li-count"><?= count($this->blocked) ?></span></li>
		<? foreach( $this->blocked as $i => $item ) : ?>
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
	        	<a rel="external" data-icon="sort-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
			<li data-role="list-divider"><?= _("Normal users") ?><span class="ui-li-count"><?= count($this->normals) ?></span></li>
		<? foreach( $this->normals as $i => $item ) : ?>
			
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
				<a rel="external" data-icon="sort-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="sort-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
			<li data-role="list-divider"><?= _("Admins") ?><span class="ui-li-count"><?= count($this->admins) ?></span></li>
			<? foreach( $this->admins as $i => $item ) : ?>
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
				<a rel="external" data-icon="sort-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-theme="d"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
		</ul>
		<br /><br />
		<div data-role="collapsible" data-content-theme="c">
		   <h3>Add partnerships</h3>
		   <form action="index.php?action=DB" method="post" data-ajax="false">
		   	  <input type="hidden" name="method" value="addPartnership">
		 	  <textarea rows="" cols="" name="data"></textarea>
		 	  <div style="text-align: center;">
		 	  <input type="submit" value="send" data-inline="true" data-theme="g"/>
		 	  </div>
		   </form>
		</div>
		
	</div>
	
</div>



<? include("footer.php"); ?>