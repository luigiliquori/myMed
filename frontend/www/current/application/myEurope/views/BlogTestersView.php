<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a href="./" data-icon="back"><?= _("Back") ?></a></li>
	      		<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
	      	</ul>
      	</div>
      	<? include("notifications.php"); ?>
	</div>

	<div data-role="content">
		<h3 class="ui-link" style="text-align: center;"><?= _('Beta Testers Blog') ?></h3>
		<ul data-role="listview" class="blog" data-inset="true" data-theme="d" data-filter="true" data-filter-placeholder="">
			<? foreach($this->messages as $k => $v) : ?>
			<?  $pieces = explode("^", $k); $c = count($pieces);?>
			<li <?= $c>3 ? "style='margin-left:".(2*($c-2))."%;'":"" ?>>
				<p><?= urldecode($v) ?></p>
				<p style="margin-top:.5em;"><a href="?action=ExtendedProfile&id=<?= $pieces[$c-1] ?>"><?= getUser($pieces[$c-1]) ?></a> <?= date('d/m/Y H:i:s', $pieces[$c-2]) ?></p>
				
				<div style="position:absolute;right:3px;bottom:-1px;" data-role="controlgroup" data-type="horizontal" data-mini="true">	
					<a href="" data-role="button" onclick="$(this).parents('li').children('form').toggle();" data-mini="true"><?= _('Reply') ?></a>
					<a href="#deleteTestersPopup" data-role="button" data-rel="popup" data-position-to="origin" 
					onclick="$('#deleteTestersYes').attr('href', '?action=Blog&blog=<?= $this->blog ?>&field=<?= $k ?>&rm=');" data-icon="arrow-d" data-iconpos="notext"><?= _('delete') ?></a>
				</div>
				<form method="post" action="?action=Blog&blog=<?= $this->blog ?>" style="text-align:right;display:none;">
					<input type="hidden" name="replyTo" value="<?= $k ?>"/>
					<textarea name="text" placeholder=""></textarea>
					<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Post') ?>" />
				</form>
			</li>
			<? endforeach ?>
		</ul>
		<br />
		<form method="post" action="?action=Blog&blog=<?= $this->blog ?>" style="text-align:right;">
			<textarea name="text" placeholder="<?= _('Respond to the blog >>>>>>html editor') ?>"></textarea>
			<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Post') ?>" />
		</form>
		 
		 <div data-role="popup" id="deleteTestersPopup" style="padding:5px;" data-overlay-theme="b" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<a data-role="button" style="color:blue;font-style: italic;" data-icon="plus" data-mini="true" onclick="var id=$('#deleteYes').attr('href');rate(1, id);">+1</a>
			<a id="deleteTestersYes" data-role="button" data-theme="d" data-icon="delete" data-mini="true">remove</a>
		</div>
		 
	</div>
</div>

<? include("footer.php"); ?>