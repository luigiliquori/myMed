<? include("header.php"); ?>
<script>
	blog = '<?= $this->blog ?>';
</script>

<div data-role="page" id="Blog">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_simple($this->blog.' '._("Blog")) ?>
      	<? include("notifications.php"); ?>
	</div>

	<div data-role="content">
	
	<br />
		
<div data-role="collapsible-set" data-theme="d" data-content-theme="d">
	<? $first=true; foreach($this->messages as $k=>$v) : ?>
	<div data-role="collapsible" <? if($first){echo('data-collapsed="false"');$first=false;} ?>>
		<h2><?= $v['title'] ?><time style="font-weight: lighter; font-size: 14px; letter-spacing: 1px; float: right;"><?= date('j/n/Y', $v['time']) ?></time></h2>
		<ul data-role="listview" data-theme="d" data-divider-theme="d">
			<li id="<?= $k ?>">
				<div style="position: absolute;">
				<a class="vote-up-off" onclick="rate($(this), '<?= $k ?>', '<?= $v['user'] ?>', 1);" title="<?= $v['up'] ?> up votes (click again to undo)">up vote</a>
				<p style="text-align: center;font-weight: bold;"><?= $v['up']-$v['down'] ?></p>
				<a class="vote-down-off" onclick="rate($(this), '<?= $k ?>', '<?= $v['user'] ?>', 0);" title="<?= $v['down']?> down votes, (click again to undo)">down vote</a>
				</div>
				<div style="padding-left: 40px; padding-top: 5px; font-weight: initial;"><?= $v['text'] ?></div>
				<p class="ui-li-aside"><a href="?action=extendedProfile&user=<?= $v['user'] ?>" data-transition="slidedown" class="user-sig"><?= prettyprintUser($v['user']) ?></a> le <time><?= date('j/n/y G:i', $v['time']) ?></time>
				 <a href="" onclick="reply($(this));">reply</a>
				 <a href="#deletePopup" data-rel="popup" data-position-to="origin" onclick="setIds($(this));" class="delete-icon" title=""></a>
				</p>
				<br />
				<div data-role="collapsible" data-mini="true" data-inset="false">
					<h2 style="width:165px;"><?= count($this->comments[$k]) ?> <?= _("comments") ?></h2>
					<div id="<?= 'comment'.$k ?>" class='comment' style="margin-bottom: 15px;">
						<textarea name="text" placeholder="add a comment"></textarea>
						<a type="button" data-inline="true" data-mini="true" data-inline="true" 
							onclick="commentAdd($(this));" ><?= _('Reply') ?></a>
					</div>
					<ul data-role="listview" data-split-icon="gear" data-split-theme="d">
					<? foreach ($this->comments[$k] as $id=>$v): ?>
						<? 
						if (empty($v['replyTo']))
							$userCommented = '';
						else if (empty($this->comments[$k][$v['replyTo']]))
							$userCommented = -1;
						else
							$userCommented = $this->comments[$k][$v['replyTo']]['user'];
						?>
						<?= comment($id, $v, $userCommented) ?>
					<? endforeach ?>
					</ul>
				</div>
				
			</li>
			
		</ul>
	</div>
	<? endforeach ?>
</div>
		
		<br />
		<div data-role="collapsible" class="loadCLE" data-mini="true" data-inline="true" style="margin-bottom: -.5em;" data-collapsed-icon="edit" data-expanded-icon="faminus">
			<h3 style="margin:auto;margin-left: 0;width:165px;"><?= _('New Message') ?></h3>
			<form method="post" action="?action=Blog&blog=<?= $this->blog ?>"  style="text-align:right;">
				<input type="text" name="title" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />
				
				<textarea id="CLEeditor" id="textBlog" name="text"></textarea>
				<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Post') ?>" />
			</form>
		</div>
		
		<div data-role="popup" id="popupInfo" data-theme="c" style="padding:10px;max-width:350px;">
          <p>Here is a <strong>tiny popup</strong> being used like a tooltip. The text will wrap to multiple lines as needed.</p>
		</div>

		<div data-role="popup" id="commentPopup" data-theme="none">
			<div data-role="controlgroup" data-mini="true">
				<a onclick="setReplyForm();" data-rel="back" data-icon="forward" data-role="button" data-theme="d" data-mini="true">reply</a>
				<a data-role="button" data-icon="thumb" onclick="var id=$('#deleteField').val();rate($(this), id, user, 1);">12</a>
				<a data-role="button" style="color:gray;" data-icon="faminus" onclick="var id=$('#deleteField').val();rate($(this), id, user, 0);">5</a>
				<a data-role="button" data-theme="d" data-icon="remove" data-mini="true">remove</a>
			</div>
		</div>
		
		<div data-role="popup" id="deletePopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<?= _('Sure?') ?><br />
			<a onclick="commentRm();" data-role="button" data-theme="d" data-icon="remove" data-inline="true">Yes</a>
		</div>
		
		<form method="post" id="deleteMessageForm" action="?action=Blog&blog=<?= $this->blog ?>" style="display:none;">
			<input type="hidden" id="deleteField" name="field" value=""/>
			<input type="hidden" id="deleteRm" name="rm" value=""/>
		</form>
		 
	</div>
</div>

<? include("footer.php"); ?>