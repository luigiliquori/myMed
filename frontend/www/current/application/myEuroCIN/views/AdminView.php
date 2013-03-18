<!-- ------------------ -->
<!-- App Admin View     -->
<!-- ------------------ -->


<? require_once('header-bar.php'); ?>

<? require_once('notifications.php'); ?>


<!-- Admin View page -->
<div data-role="page" id="adminview">

	<!-- Header bar-->
  	<? $title = _("Manage accounts ");
	   print_header_bar("?action=main", "defaultHelpPopup", $title); ?>
	
	<!-- Page content -->
	<div data-role="content">
		<!-- Collapsible description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Manage associations capsule title") ?></h3>
			<p><?= _("Manage associations capsule text")?></p>
		</div>

		<br>
		
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="c" data-mini="true">
			<input id="user_permission" name="permission" value="" type="hidden" />
			<input id="user_id" name="id" value="" type="hidden" />
		
			<ul data-role="listview" >
				<li data-role="list-divider"><?= _("Normal users") ?><span class="ui-li-count"><?= count($this->normals) ?></span></li>
			<? foreach( $this->normals as $i => $item ) : ?>
				
				<li>
					<a href="?action=ExtendedProfile&method=show_user_profile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
					<a href="#" onclick='delete_user_popup("<?= urlencode($item->id) ?>", "<?= $item->permission - 1 ?>", "<?= $item->email ?>");' data-theme="r" data-icon="delete" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= _('Delete profile') ?></a>
					<a rel="external" data-icon="sort-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>&email=<?= $item->email ?>" data-theme="g"><?= _("Make this account admin") ?></a>

					<script type="text/javascript">
						function delete_user_popup(id, perm, email){
							$("#popupDelete").html('<p style="font-size:85%;"><?= _("Are you sure you want to delete this account?") ?></p>\
								<fieldset class="ui-grid-a">\
									<div class="ui-block-a">\
										<a data-icon="ok" data-theme="g" href="?action=Admin&method=delete&id='+id+'&perm='+perm+'&email='+email+'" data-inline="true" data-role="button"><?= _("Yes") ?></a>\
									</div>\
									<div class="ui-block-b">\
										<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>\
									</div>\
								</fieldset>\
								');
							$("#popupDelete").trigger('create');
				 			$("#popupDelete").popup("open");
						}
					</script>
					<div data-role="popup" id="popupDelete" class="ui-content" Style="text-align: center; width: 18em;"></div>
				</li>
			<? endforeach ?>
				<li data-role="list-divider"><?= _("Admins") ?><span class="ui-li-count"><?= count($this->admins) ?></span></li>
				<? foreach( $this->admins as $i => $item ) : ?>
				<li>
					<a href="?action=ExtendedProfile&method=show_user_profile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
					<a rel="external" data-icon="sort-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>&email=<?= $item->email ?>&promoted=false" data-theme="e"><?= _("Remove admin rights") ?></a>
				</li>
			<? endforeach ?>
			</ul>
		</div>
	</div>
	
	
	<!-- Help popup -->
	<div data-role="popup" id="adminviewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Manage associations") ?></h3>
		<p> <?= _("Here you can change associations category. <br /> Categories are: 'Admin', 'Validated association' and 'Not validated association'.") ?></p>
		
	</div>
	
</div>
