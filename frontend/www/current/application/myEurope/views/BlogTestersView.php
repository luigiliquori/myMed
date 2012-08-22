<? include("header.php"); ?>

<div data-role="page" id="BlogTesters">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a data-rel="back" rel="external" data-theme="d" data-icon="back"><?= _("Back") ?></a></li>
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
				<div><?= $v->text ?></div>
				<p style="margin-top:.5em;"><a href="?action=ExtendedProfile&id=<?= $pieces[1] ?>"><?= getUser($pieces[1]) ?></a> <?= date('j/n/y G:i', $pieces[0]) ?></p>
				<span style="color:blue;font-style: italic;position:absolute;right:60px;top:12px;">+0</span>
				<div style="position:absolute;right:6px;top:2px;" data-role="controlgroup" data-type="horizontal" data-mini="true">	
					<a href="#deleteTestersPopup" data-role="button" data-rel="popup" data-position-to="origin" data-inline="true"
						onclick="$('#deleteField').val('<?= $k ?>');" data-icon="arrow-d" data-iconpos="notext"><?= _('plus') ?></a>
				</div>
				
				<div data-role="collapsible" data-mini="true" data-inline="true" style="margin-bottom: -.5em;">
					<h3 style="margin:auto;margin-left: 0;width:136px;"><?= count($this->comments[$k])." "._("comments") ?></h3>
					<? foreach($this->comments[$k] as $ki => $vi) : ?>
						<?  $piecesi = explode("^", $ki);$vi=json_decode($vi);$piecesj = explode("^", $vi->replyTo); ?>
						<div id="<?= hash("crc32",$ki) ?>" data-reply="<?= hash("crc32",$vi->replyTo) ?>" <?= empty($vi->replyTo)?'class="root"':'' ?>>
							<p style="margin-top:.5em;margin-bottom:-.4em;"><?= $vi->text ?></p>
							<p style="margin-top:.5em;display:inline-block;"><a href="?action=ExtendedProfile&id=<?= $piecesi[1] ?>"><?= getUser($piecesi[1]) ?></a> 
							<? if(!empty($vi->replyTo)) : ?>
								 in reply to <a href="#" class="comments"><?= strshorten(getUser($piecesj[1])) ?></a>
							<? endif ?>
							 <?= date('j/n/y G:i', $piecesi[0]) ?></p>
							<a href="#deleteTestersPopup2" data-role="button" data-rel="popup" data-position-to="origin" data-inline="true"
								onclick="$('#deleteField').val('<?= $ki ?>');$('#deleteRm').val('<?= $k ?>');$('#deleteFieldhash').val('<?= hash("crc32",$ki) ?>');" data-icon="arrow-d" data-iconpos="notext"><?= _('plus') ?></a>
							<span style="color:blue;font-style: italic;">+0</span>
							<form method="post" action="?action=Blog&blog=<?= $this->blog ?>" style="text-align:right;display: none;">
								<input type="hidden" name="commentTo" value="<?= $k ?>"/>
								<input type="hidden" name="replyTo" value="<?= $ki ?>"/>
								<textarea name="text" placeholder="add a reply" onfocus="$('.replyButton').show();"></textarea>
								<div style="display: none;" class="replyButton">
									<input type="submit" data-mini="true" data-inline="true" value="<?= _('Reply') ?>" />
								</div>
							</form>
						</div>
					<? endforeach ?>
					<form method="post" action="?action=Blog&blog=<?= $this->blog ?>" style="text-align:right;">
						<input type="hidden" name="commentTo" value="<?= $k ?>"/>
						<input type="hidden" name="replyTo" value="<?= null ?>"/>
						<textarea name="text" placeholder="add a comment" onfocus="$('.replyButton').show();"></textarea>
						<div style="display: none;" class="replyButton">
							<input type="submit" data-mini="true" data-inline="true" value="<?= _('Reply') ?>" />
						</div>
					</form>
				</div>
				
				
			</li>
			<? endforeach ?>
		</ul>
		<br />
		<div data-role="collapsible" data-mini="true" data-inline="true" style="margin-bottom: -.5em;" data-collapsed-icon="gear">
			<h3 style="margin:auto;margin-left: 0;width:156px;"><?= _('New Message') ?></h3>
			<form method="post" action="?action=Blog&blog=<?= $this->blog ?>"  style="text-align:right;">
				<input type="text" name="title" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />
				
				<textarea id="CLEeditor" id="textBlog" name="text"></textarea>
				<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Post') ?>" />
			</form>
		</div>
		
		 
		 <div data-role="popup" id="deleteTestersPopup" style="padding:10px;" data-theme="e">
			<a data-role="button" style="color:blue;font-style: italic;" data-icon="thumb" data-mini="true" onclick="var id=$('#deleteField').val();rate(1, id);">+1</a>
			<a onclick="$('#deleteMessageForm').submit();" data-role="button" data-theme="d" data-icon="delete" data-mini="true">remove</a>
		</div>
		
		<div data-role="popup" id="deleteTestersPopup2" style="padding:5px;"  data-theme="e">
			<a onclick="$('#'+$('#deleteFieldhash').val()).find('form').toggle();" data-rel="back" data-role="button" data-theme="d" data-mini="true" data-inline="true">reply</a>
			<a data-role="button" style="color:blue;font-style: italic;" data-icon="thumb" data-mini="true" onclick="var id=$('#deleteField').val();rate(1, id);" data-inline="true">+1</a>
			<a onclick="$('#deleteMessageForm').submit();" data-role="button" data-theme="d" data-icon="delete" data-mini="true" data-inline="true">remove</a>
		</div>
		
		<form method="post" id="deleteMessageForm" action="?action=Blog&blog=<?= $this->blog ?>" style="display:none;">
			<input type="hidden" id="deleteField" name="field" value=""/>
			<input type="hidden" id="deleteRm" name="rm" value=""/>
			<input type="hidden" id="deleteFieldhash" value=""/>
		</form>
		 
	</div>
</div>

<? include("footer.php"); ?>