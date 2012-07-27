<? include("header.php"); ?>

<?
function tab_bar_white($activeTab) {
	tabs_white_back(array(
			"users" => array("Utilisateurs", "profile"),
			"contents" => array("Contenus", "grid")
		),
		$activeTab);
} 
?>

<div data-role="page" id="users">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("users") ?>
	</div>
	
	<div data-role="content" style="text-align:center;">
	
		<p style="text-align:center;">
			[0-]: Nouvel utilisateur en attente de validation <br />
			[1]: utilisateur normal <br />
			[2+]: admin
		</p>
		
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		<ul data-role="listview" data-inset="true" data-filter-placeholder="...">
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
				
				<a href=""> <?= $item->id ?> <span style="color:red;"><?= $item->permission ?></span> </a>
				<a data-icon="minus" onclick="$('#userDownForm<?= $i ?>').submit();" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
	        	<a data-icon="plus" onclick="$('#userUpForm<?= $i ?>').submit();" data-theme="e">Augmenter</a> 

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
		<ul data-role="listview" data-theme="d" data-filter="true" data-filter-placeholder="filtrer parmi les résultats">
			<li data-role="list-divider" data-mini="true">Pending</li>
			<li><a href="?action=detail&namespace=partPending&id=&user=" 
			 style="padding-top: 1px; padding-bottom: 1px;">
					<h3>
						projet21
					</h3>
					<p style="font-weight:lighter;"> ......... </p>
					<p class="ui-li-aside">
						publié par: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;">......</span> échéance: <strong>...</strong>
					</p>

			</a>
			</li>
			<li data-role="list-divider" data-mini="true" data-theme="c">Validated</li>
		</ul>
	</div>
</div>


<? include("footer.php"); ?>