<? include("header.php"); ?>

<div data-role="page" id="Blog">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a href="./" rel="external" data-theme="d" data-icon="back"><?= _("Back") ?></a></li>
	      		<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
	      	</ul>
      	</div>
	</div>
	<div data-role="header" data-theme="c" data-position="fixed">
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content">
		<br />
		<h1 style="font-family: Trebuchet MS;"><?= $this->blog.' '._("Blog") ?></h1>
		
		
<div data-role="collapsible-set" data-theme="d" data-content-theme="d">
	<? $first=true; foreach($this->messages as $k=>$v) : ?>
	<div data-role="collapsible">
		<h2><?= $v['title'] ?> &ndash; <a onclick="profile('<?= $v['user'] ?>');"><?= prettyprintUser($v['user']) ?></a></h2>
		<ul data-role="listview" data-theme="d" data-divider-theme="d">
			<li>
				<h3><?= $v['title'] ?></h3>
				
				<p><?= $v['text'] ?></p>
				<div style="position:absolute;right:3px;top:4px;" data-role="controlgroup" data-mini="true">	
					<a href="#commentPopup" data-role="button" data-rel="popup" data-position-to="origin"
						onclick="setCommentForm('<?= $k ?>', '', '<?= $v['up'] ?>', '<?= $v['down']?>');" data-icon="gear" data-iconpos="notext">options</a>
				</div>
				<p class="ui-li-aside" style="margin-right: 30px;"><time><?= date('j/n/y G:i', $v['time']) ?></time></p>
				<br />
				<div data-role="collapsible" data-mini="true" data-inset="false">
					<h2 style="width:165px;"><?= count($this->comments[$k]) ?> <?= _("comments") ?></h2>
					<ul data-role="listview" id="<?= 'comments'.$k ?>" data-split-icon="gear" data-split-theme="d">
						<?= comments($this->comments[$k], $k) ?>
					</ul>
					<form method="post" id="<?= 'commentForm'.$k ?>" action="?action=Blog&blog=<?= $this->blog ?>" style="text-align:right;padding-top: 10px;">
						<input type="hidden" name="commentTo" value="<?= $k ?>"/>
						<input type="hidden" id="replyTo" name="replyTo" value="0"/>
						<textarea name="text" placeholder="add a comment" onfocus="$('.replyButton').show();"></textarea>
						<div style="display: none;" class="replyButton">
							<a type="button" data-inline="true" data-mini="true" data-inline="true" 
							onclick="commentAdd('<?= $this->blog ?>', '<?= $k ?>', $('#replyTo').val(), $(this).closest('textarea').val())" ><?= _('Reply') ?></a>
						</div>
					</form>
				</div>
			</li>
			
		</ul>
	</div>
	<? endforeach ?>
</div>
		
		<br />
		<div data-role="collapsible" class="loadCLE" data-mini="true" data-inline="true" style="margin-bottom: -.5em;">
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
				<a data-role="button" data-icon="thumb" onclick="var id=$('#deleteField').val();rate(1, id, user);">12</a>
				<a data-role="button" style="color:gray;" data-icon="minus" onclick="var id=$('#deleteField').val();rate(0, id, user);">5</a>
				<a onclick="if($('#deleteRm').val()==''){$('#deleteMessageForm').submit();}else{commentRm('<?= $this->blog ?>', $('#deleteRm').val(), $('#deleteField').val());}" data-role="button" data-theme="d" data-icon="delete" data-mini="true">remove</a>
			</div>
		</div>
		
		<form method="post" id="deleteMessageForm" action="?action=Blog&blog=<?= $this->blog ?>" style="display:none;">
			<input type="hidden" id="deleteField" name="field" value=""/>
			<input type="hidden" id="deleteRm" name="rm" value=""/>
		</form>
		 
	</div>
</div>

<? include("footer.php"); ?>