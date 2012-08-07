<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a href="./" data-icon="back"><?= _("Back") ?></a></li>
	      		<li><a href="?action=extendedProfile" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
	      	</ul>
      	</div>
	</div>

	
	<div data-role="content">
		<h3 class="ui-link" style="text-align: center;"><?= _('Alcotra Blog') ?></h3>
		<div data-role="navbar" data-theme="e" data-iconpos="left"> 
			<ul>
				<li><a href="" class="ui-btn-active ui-state-persist"><?= _("Theme1") ?></a></li>
				<li><a href=""><?= _("Theme2") ?></a></li>
				<li><a href=""><?= _("Theme3") ?></a></li>
				<li><a href=""><?= _("Theme4") ?></a></li>
				<li><a href=""><?= _("Theme5") ?></a></li>
			</ul>
		</div>
		<br />
		<ul data-role="listview" class="blog" data-inset="true" data-theme="d" data-filter="true" data-filter-placeholder="">
			<? foreach($this->messages as $k => $v) : ?>
			<? $pieces = explode("_", $k, 3); ?>
			<li>
				<h3><?= $pieces[2] ?>:</h3>
				<p><?= urldecode($v) ?></p>
				<p class="ui-li-aside"><strong><?= date('d/m/Y', $pieces[0]) ?></strong> <?=  date('H:i:s', $pieces[0]) ?></p>
				<span><a href="?action=Blog&blog=<?= $this->blog ?>&field=<?= $k ?>&rm=" style="position:absolute;right:5px;bottom:5px;"><?= _('delete') ?></a></span>
			</li>
			<? endforeach ?>
		</ul>
		<br />
		<form method="post" action="?action=Blog&blog=<?= $this->blog ?>">
			<input type="hidden" name="action" value="Blog" />
			<input type="hidden" name="blog" value="<?= $this->blog ?>" />
			<textarea name="text"></textarea>
			<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('reply') ?>" />
		</form>
		 
	</div>
</div>

<? include("footer.php"); ?>