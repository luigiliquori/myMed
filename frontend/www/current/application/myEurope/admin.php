
<?php
require_once 'Template.php';
Template::init();
if ($_SESSION['profile']->permission < 2){
	exit();
	return;
}

$i = 0;

if (isset($_POST['permission'], $_POST['id'])){

	$data = array();
	array_push($data, new DataBean("permission", DATA, $_POST['permission']));
	
	$request = new Request("v2/PublishRequestHandler", UPDATE);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("namespace", "users");

	$request->addArgument("data", json_encode($data));
	$request->addArgument("user", $_SESSION['user']->id);
	$request->addArgument("id", urldecode($_POST['id']));
	$responsejSon = $request->send();

}

$data=array();
$request = new Request("v2/FindRequestHandler", READ);
$request->addArgument("application", Template::APPLICATION_NAME);
$request->addArgument("namespace", "users");
$request->addArgument("index", json_encode($data));

$responsejSon = $request->send();
$res = json_decode($responsejSon);

if($res->status == 200) {
	$res = $res->dataObject->results;
} else {
	$res = array();
}

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
				<li><a href="home"  data-icon="home" rel="external" data-transition="slidefade"><?= _('Home') ?></a></li>
				<li><a href="#AdminUsers" data-icon="info" class="ui-btn-active ui-state-persist">Users</a></li>
				<li><a href="#AdminContents" data-icon="info">Contents</a></li>
			</ul>
		</div>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<h2 style="text-align:center;">
			<a href="" style="text-decoration: none;">Utilisateurs </a>
		</h2>
		<p style="text-align:center;">
			[0-]: Nouvel utilisateur en attente de validation <br />
			[1]: utilisateur normal <br />
			[2+]: admin
		</p>
		
		<form action="#" method="post" id="userForm">
			<input id="user_permission" name="permission" value="" type="hidden" />
			<input id="user_id" name="id" value="" type="hidden" />
			<ul data-role="listview" data-inset="true" data-filter-placeholder="...">
			<?php 
			foreach( $res as $k => $value ){
			?>
				<li>
					<a href=""> <?= $value->id ?> [<?= $value->permission ?>] </a>
					<a data-icon="minus" onclick="$('#user_permission').val(<?= $value->permission - 1 ?>);$('#user_id').val('<?= urlencode($value->id) ?>');$('#userForm').submit();"  data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"> Diminuer</a>
		        	<a data-icon="plus" onclick="$('#user_permission').val(<?= $value->permission + 1 ?>);$('#user_id').val('<?= urlencode($value->id) ?>');$('#userForm').submit();" data-theme="e">Augmenter</a> 

				</li>
			<?php 
			}
			?>		
			</ul>
		</form>
	</div>

</div>	

<div id="AdminContents" data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c" data-iconpos="left">
			<ul>
				<li><a href="./"  data-icon="home" data-transition="slidefade"><?= _('Home') ?></a></li>
				<li><a href="#AdminUsers" data-icon="info">Users</a></li>
				<li><a href="#AdminContents" data-icon="info" class="ui-btn-active ui-state-persist">Contents</a></li>
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

</body>
</html>
