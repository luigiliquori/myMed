<? include("header.php"); ?>

<?
function tab_bar_white($activeTab) {
	tabs_white_back(array(
			"users" => array(_("Users"), "profile"),
			"contents" => array(_("Contents"), "grid")
		),
		$activeTab);
} 
?>

<div data-role="page" id="users">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("users") ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content" style="text-align:center;">
	
		<p style="text-align:center;">
			[<span style="color:red;">0</span>-]: <?= _("New user waiting for validation") ?><br />
			[<span style="color:red;">1</span>]:  <?= _("Normal user") ?> <br />
			[<span style="color:red;">2</span>+]: <?= _("Admin") ?>
		</p>
		
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		<ul data-role="listview" data-inset="true" data-filter-placeholder="<?= _("Filter results") ?>">
		<? foreach( $this->users as $i => $item ){ ?>
			<li>
				<form action="?action=Admin" method="post" id="userUpForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->permission + 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
				</form>
				<form action="?action=Admin" method="post" id="userDownForm<?= $i ?>" data-ajax="false">
					<input type="hidden" name="perm" value="<?= $item->permission - 1 ?>" />
					<input type="hidden" name="id" value="<?= $item->id ?>" />
					
				</form>
				
				<a href=""> <?= getUser($item->id) ?> <span style="color:red;"><?= $item->permission ?></span> </a>
				<a data-icon="minus" onclick="$('#userDownForm<?= $i ?>').submit();" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
	        	<a data-icon="plus" onclick="$('#userUpForm<?= $i ?>').submit();" data-theme="e"><?= _('Increase') ?></a> 

			</li>
		<? } ?>		
		</ul>
		
	</div>

</div>

<div data-role="page" id="contents">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("contents") ?>
	</div>
	<div data-role="content">
		<ul data-role="listview" data-theme="d" data-filter="true" data-filter-placeholder="<?= _("Filter results") ?>">
			
		</ul>
	</div>
</div>


<? include("footer.php"); ?>