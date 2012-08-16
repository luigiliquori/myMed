<? include("header.php"); ?>
<link rel="stylesheet" type="text/css" href="../../lib/jquery/CLEeditor/jquery.cleditor.css" />
<script type="text/javascript" src="../../lib/jquery/CLEeditor/jquery.cleditor.js"></script>
<script type="text/javascript" src="../../lib/jquery/CLEeditor/startCLE.js"> </script>

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
			<?  $pieces = explode("^", $k); $v=json_decode($v); ?>
			<li>
				<h1><?= $v->title ?></h1>
				<p><?= $v->text ?></p>
				<p style="margin-top:.5em;"><a href="?action=ExtendedProfile&id=<?= $pieces[1] ?>"><?= getUser($pieces[1]) ?></a> <?= date('d/m/Y H:i:s', $pieces[0]) ?></p>
				

				<div style="position:absolute;right:6px;top:2px;" data-role="controlgroup" data-type="horizontal" data-mini="true">	
					<a href="#deleteTestersPopup" data-role="button" data-rel="popup" data-position-to="origin" data-inline="true"
						onclick="$('#deleteField').val('<?= $k ?>');" data-icon="arrow-d" data-iconpos="notext"><?= _('plus') ?></a>
				</div>
				
				<div data-role="collapsible" data-mini="true" data-inline="true" style="margin-bottom: -.5em;">
					<h3 style="margin:auto;margin-left: 0;width:136px;"><?= count($this->comments[$k])." "._("comments") ?></h3>
					<? foreach($this->comments[$k] as $ki => $vi) : ?>
						<?  $piecesi = explode("^", $ki);?>
						<div>
							<p style="margin-top:.5em;margin-bottom:0"><?= urldecode($vi->text) ?></p>
							<p style="margin-top:.5em;display:inline-block;"><a href="?action=ExtendedProfile&id=<?= $piecesi[1] ?>"><?= getUser($piecesi[1]) ?></a> <?= date('d/m/Y H:i:s', $piecesi[0]) ?></p>
							<a href="#deleteTestersPopup" data-role="button" data-rel="popup" data-position-to="origin" data-inline="true"
								onclick="$('#deleteField').val('<?= $k."^reply^".$ki ?>');" data-icon="arrow-d" data-iconpos="notext"><?= _('plus') ?></a>
						</div>
					<? endforeach ?>
					<form method="post" action="?action=Blog&blog=<?= $this->blog ?>" style="text-align:right;">
						<input type="hidden" name="replyTo" value="<?= $k ?>"/>
						<textarea name="text" placeholder=""></textarea>
						<input type="submit" data-mini="true" data-inline="true" value="<?= _('Reply') ?>" />
					</form>
				</div>
				
				
			</li>
			<? endforeach ?>
		</ul>
		<br />
		<div data-role="collapsible" data-mini="true" data-inline="true" style="margin-bottom: -.5em;">
			<h3 style="margin:auto;margin-left: 0;width:136px;"><?= _('Respond') ?></h3>
			<form method="post" action="?action=Blog&blog=<?= $this->blog ?>"  style="text-align:right;">
				<input type="text" name="title" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />
				
				<textarea id="CLEeditor" id="textBlog" name="text"></textarea>
				<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Post') ?>" />
			</form>
		</div>
		
		 
		 <div data-role="popup" id="deleteTestersPopup" style="padding:5px;" data-overlay-theme="b" data-theme="d">
			<a data-role="button" style="color:blue;font-style: italic;" data-icon="plus" data-mini="true" onclick="var id=$('#deleteYes').attr('href');rate(1, id);">+1</a>
			<a onclick="$('#deleteMessageForm').submit();" data-role="button" data-theme="d" data-icon="delete" data-mini="true">remove</a>
		</div>
		
		<form method="post" id="deleteMessageForm" action="?action=Blog&blog=<?= $this->blog ?>" style="display:none;">
			<input type="hidden" id="deleteField" name="field" value=""/>
			<input type="hidden" name="rm" value=""/>
		</form>
		 
	</div>
</div>

<? include("footer.php"); ?>