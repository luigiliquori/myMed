
<?php
require_once 'Template.php';
Template::init(false);

$i = 0;
?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>
<body>
<div id="AdminUsers" data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c" data-iconpos="left">
			<ul>
				<li><a href="./"  data-icon="home" rel="external" data-transition="slidefade"><?= _('Home') ?></a></li>
				<li><a href="#AdminUsers" data-icon="info" class="ui-btn-active ui-state-persist">Users</a></li>
				<li><a href="#AdminContents" data-icon="info">Contents</a></li>
				<li><a href="#AdminComments" data-icon="info" >Comments</a></li>
			</ul>
		</div>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<h2 style="text-align:center;">
			<a href="" style="text-decoration: none;">Users List Permissions</a>
		</h2>
		
		<p style="text-align:center;">
			[0]: all users <br />
			[1]: access to admin
		</p>
		<ul data-role="listview" data-inset="true" data-filter-placeholder="...">
				<li>
					<a href=""> Joe [1]</a>
					<a data-icon="minus" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();"  data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
        			<a data-icon="plus" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();" data-theme="e">Augmenter</a> 
					<form action="#" method="post" id="deleteSubscriptionForm<?= $i ?>">
						<input name="application" value='<?= Template::APPLICATION_NAME."part" ?>' type="hidden" />
						<input name="predicate" value="" type="hidden" />
						<input name="userID" value='<?= $_SESSION['user']->id ?>' type="hidden" />
					</form>
				</li>
				<li>
					<a href=""> Sarah [1]</a>
					<a data-icon="minus" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();"  data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
        			<a data-icon="plus" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();" data-theme="e">Augmenter</a> 
					<form action="#" method="post" id="deleteSubscriptionForm<?= $i ?>">
						<input name="application" value='<?= Template::APPLICATION_NAME."part" ?>' type="hidden" />
						<input name="predicate" value="" type="hidden" />
						<input name="userID" value='<?= $_SESSION['user']->id ?>' type="hidden" />
					</form>
				</li>
				<li>
					<a href=""> Mike [0]</a>
					<a data-icon="minus" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();"  data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
        			<a data-icon="plus" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();" data-theme="e">Augmenter</a> 
					<form action="#" method="post" id="deleteSubscriptionForm<?= $i ?>">
						<input name="application" value='<?= Template::APPLICATION_NAME."part" ?>' type="hidden" />
						<input name="predicate" value="" type="hidden" />
						<input name="userID" value='<?= $_SESSION['user']->id ?>' type="hidden" />
					</form>
				</li>
			</ul>
	</div>

</div>	

<div id="AdminContents" data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c" data-iconpos="left">
			<ul>
				<li><a href="./"  data-icon="home" data-transition="slidefade"><?= _('Home') ?></a></li>
				<li><a href="#AdminUsers" data-icon="info">Users</a></li>
				<li><a href="#AdminContents" data-icon="info" class="ui-btn-active ui-state-persist">Contents</a></li>
				<li><a href="#AdminComments" data-icon="info" >Comments</a></li>
			</ul>
		</div>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<h2 style="text-align:center;">
			<a href="" style="text-decoration: none;">Contents List</a>
		</h2>
		
		<ul data-role="listview" data-theme="d" data-filter="true" data-filter-placeholder="filtrer parmi les résultats">
				<li data-role="list-divider" data-mini="true">Pending</li>
				<li><a href="detail?id=<?=  urlencode($value->predicate) ?>&user=<?=  urlencode($value->publisherID) ?>&application=<?= urlencode($application) ?>" 
				 style="padding-top: 1px; padding-bottom: 1px;">
						<h3>
							projet: <?= $value->predicate ?>
						</h3>
						<p>
							réputation: <?= 100 - $value->rate ?>
						</p>
						<p style="font-weight:lighter;"> métiers: <?= join(", ",$metiers) ?>...
						 régions: <?= join(", ", $regions) ?>... </p>
						<p class="ui-li-aside">
							publié par: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $value->publisherName ?> </span> échéance: <strong><?= date("Y-m-d h:i:s", $value->date) ?> </strong>
						</p>

				</a>
				</li>
				<li data-role="list-divider" data-mini="true" data-theme="c">Validated</li>
			</ul>
		
	</div>

</div>

<div id="AdminComments" data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c" data-iconpos="left">
			<ul>
				<li><a href="./"  data-icon="home" data-transition="slidefade"><?= _('Home') ?></a></li>
				<li><a href="#AdminUsers" data-icon="info">Users</a></li>
				<li><a href="#AdminContents" data-icon="info">Contents</a></li>
				<li><a href="#AdminComments" data-icon="info" class="ui-btn-active ui-state-persist" >Comments</a></li>
			</ul>
		</div>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<h2 style="text-align:center;">
			<a href="" style="text-decoration: none;">Comments List</a>
		</h2>
	</div>

</div>

</body>
</html>
