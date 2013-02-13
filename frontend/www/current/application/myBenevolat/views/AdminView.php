<!-- ------------------ -->
<!-- App Admin View     -->
<!-- ------------------ -->


<? require_once('header-bar.php'); ?>

<? require_once('notifications.php'); ?>


<!-- Admin View page -->
<div data-role="page" id="adminview">

	<!-- Header bar-->
  	<? $title = _("Manage assotiations ");
	   print_header_bar("?action=main", "defaultHelpPopup", $title); ?>
	   
	<!-- Filter -->
	<div data-role="header" data-theme="e">
		<br/>
		<h1 style="white-space: normal;">
			<?= _("Validate associations") ?>
		</h1>
		<br/>
	</div>
	
	<!-- Page content -->
	<div data-role="content" >
			
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
	
		<ul data-role="listview" data-filter="true" >
			<li data-role="list-divider"><?= _("New associations waiting for validation") ?><span class="ui-li-count"><?= count($this->blocked) ?></span></li>
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
		<!-- <div data-role="collapsible" data-content-theme="c">
		   <h3><?= _("Add partnerships")?></h3>
		   <form action="index.php?action=DB" method="post" data-ajax="false">
		   	  <input type="hidden" name="method" value="addPartnership">
		 	  <textarea rows="" cols="" name="data"></textarea>
		 	  <div style="text-align: center;">
		 	  <input type="submit" value="send" data-inline="true" data-theme="g"/>
		 	  </div>
		   </form>
		</div>-->
		
	</div>
	
	
	<!-- Help popup -->
	<div data-role="popup" id="adminviewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Manage associations") ?></h3>
		<p> <?= _("Here you can change associations category. <br /> Categories are: 'Admin', 'Validated association' and 'Not validated association'.") ?></p>
		
	</div>
	
</div>



<? include("footer.php"); ?>