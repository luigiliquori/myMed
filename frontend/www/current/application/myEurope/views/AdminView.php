<? include("header.php"); ?>

<?
function tab_bar_white($activeTab) {
	tabs_white_back(array(
			"users" => array(_("Users"), "profile")
		),
		$activeTab);
} 
?>

<div data-role="page" id="users">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("users") ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content">
	
		
		
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		
		<p><?= _("New users waiting for validation") ?>:</p>
		<ul data-role="listview" data-inset="true">
		<? foreach( $this->blocked as $i => $item ) : ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->profile->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->profile->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
					
				</form>
				
				<a href="#<?= $item->id ?>"> <?= getUser($item->id) ?> <span style="color:red;"><?= $item->profile->permission ?></span> </a>
				<a data-icon="minus" onclick="$('#userDownForm<?= $i ?>').submit();" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
	        	<a data-icon="plus" onclick="$('#userUpForm<?= $i ?>').submit();" data-theme="e"><?= _('Increase') ?></a> 

			</li>
		<? endforeach ?>
		</ul>
		
		<p><?= _("Normal users") ?>:</p>
		<ul data-role="listview" data-inset="true">
		<? foreach( $this->normals as $i => $item ) : ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->profile->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->profile->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
					
				</form>
				
				<a href="#<?= $item->id ?>"> <?= getUser($item->id) ?> <span style="color:red;"><?= $item->profile->permission ?></span> </a>
				<a data-icon="minus" onclick="$('#userDownForm<?= $i ?>').submit();" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
	        	<a data-icon="plus" onclick="$('#userUpForm<?= $i ?>').submit();" data-theme="e"><?= _('Increase') ?></a> 

			</li>
		<? endforeach ?>	
		</ul>
		
		<p><?= _("Admins") ?>:</p>
		<ul data-role="listview" data-inset="true">
		<? foreach( $this->admins as $i => $item ) : ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->profile->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->profile->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
					
				</form>
				
				<a href="#<?= $item->id ?>"> <?= getUser($item->id) ?> <span style="color:red;"><?= $item->profile->permission ?></span> </a>
				<a data-icon="minus" onclick="$('#userDownForm<?= $i ?>').submit();" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
	        	<a data-icon="plus" onclick="$('#userUpForm<?= $i ?>').submit();" data-theme="e"><?= _('Increase') ?></a> 

			</li>
		<? endforeach ?>	
		</ul>
		
	</div>

</div>

<? foreach( $this->users as $item ) :?>
<div data-role="page" id="<?= $item->id ?>">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2empty(getUser($item->id)) ?>
	</div>
	<div data-role="content">
		<?= printProfile($item->profile) ?>
	</div>
</div>

<? endforeach ?>



<? include("footer.php"); ?>