<? include("header.php"); ?>
</head>
<body>

<div data-role="page" id="Search" data-theme="b">
	<div data-role="header" data-theme="b" data-position="fixed">
		<h1 style="color: white;"><?= _("Search") ?></h1>
		<a href="?action=main" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
		<? include_once "notifications.php"; ?>
	</div>
		
		<div data-role="content">
			<br />
			<center><a href="index.php?action=search" data-icon="search" data-inline="true" data-role="button" data-ajax="false"><?=_("View all")?></a></center>
			<br>
			<ul data-role="listview" data-filter="true" data-theme="d" data-inset="true" data-filter-placeholder="...">
					
				<? if (count($this->result) == 0) :?>
				<li>
					<h4><?= _('No result found') ?></h4>
				</li>
				<? endif ?>
				
				<? foreach($this->result as $item) : ?>
				<li><a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">	
					<?=  $item->publisherName ?>, <?= _($item->pred2) ?>, <?= $item->pred3 ?>
				</a></li>
				<? endforeach ?>				
			</ul>
				
			<div data-role="collapsible" data-collapsed="true" data-content-theme="d">
				<h3><?= _('Advanced searching') ?></h3>
				<form action="index.php?action=search" method="POST" data-ajax="false">
			
					<label for="textinputs1"> <?= _('Category') ?>: </label> 
	                <select name="pred2" id="textinputs1" data-theme="d" data-native-menu="false" data-overlay-theme="d">
	                   	<option value=""><?= _("Select category") ?></option>
	                    <option value="Agenda"><?= _("Agenda") ?></option>
	                    <option value="News"><?= _("News") ?></option>
	                    <option value="Enterprises"><?= _("Enterprises") ?></option>
	                    <option value="Jobs"><?= _("Jobs") ?></option>
	                    <option value="Internships"><?= _("Internships") ?></option>
	                    <option value="Visit an enterprise"><?= _("Visit an enterprise") ?></option>
	                    <option value="Projects/partnership"><?= _("Projects/partnership") ?></option>
	                    <option value="Office rental"><?= _("Office rental") ?></option>
	                </select>
					<br>
					
					<label for="textinputs2"> <?= _('Title') ?>: </label> 
					<input id="textinputs2"  name="pred3" placeholder="" type="text" />
					<br>
					
					<input type="hidden" name="method" value="Rechercher" />
					<center><input type="submit" value="<?= _('Search') ?>" data-icon="search" data-inline="true" data-theme="b"/></center>
				</form>
			</div>	
				
		</div>
</div>
</body>