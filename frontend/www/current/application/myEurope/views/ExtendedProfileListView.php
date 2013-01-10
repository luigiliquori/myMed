<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="profiles">
	
	<? print_header_bar(true, "defaultHelpPopup"); ?>

	<div data-role="content">
		<br>
		<? if ($_SESSION['myEurope']->permission <= 0): ?>
			<div data-role="header" data-theme="e">
			
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time on myEurope! Please register with an already existing profile or create yours") ?></h1>
			</div>
			<br />			
		<? endif; ?>
		<a href="?action=ExtendedProfile&new" type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="pencil" style="float: right;"><?= _("Create") ?></a>
		<div style="margin-top: 30px;"></div>
		<ul data-role="listview" data-filter="true" data-inset="true" data-mini="true" data-filter-placeholder="<?= _("filter") ?>">
		<? foreach ($this->cats as $k=>$v) :?>
			<? if (!empty($v)) :?>
				<li data-role="list-divider"><?= Categories::$roles[$k] ?></li>
				<? foreach ($v as $ki=>$vi) :?>
					<li><a href="?action=ExtendedProfile&id=<?= $vi->id ?>&link"><?= $vi->name ?></a></li>
				<? endforeach ?>
			<? endif ?>
		<? endforeach ?>
		</ul>
	</div>
	
</div>


<? include("footer.php"); ?>

