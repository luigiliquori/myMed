<script>
	blog = '<?= $this->blog ?>';
</script>

<div data-role="page" id="BlogDetailsView" data-ajax="false">
	
			<script type="text/javascript">
				// Dictionnary of already initliazed pages
				gInitialized = {}
				// Initialization made on each page 
				$('[data-role=page]').live("pagebeforeshow", function() {
				var page = $("BlogDetailsView");
				var id = page.attr("id");
				// Don't initialize twice
				if (id in gInitialized) return;
				gInitialized[id] = true;
				//debug("init-page : " + page.attr("id"));
				console.log('hello');
				$("#CLEeditor").cleditor({width:500, height:180, useCSS:true})[0].focus();
				});
		</script>

	<? tabs_simple($this->blog, 'Back'); ?>
	
	
	<div data-role="content" data-ajax="false">
		
		<a type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign" style="float: right;
		    "onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:BlogDetails", "<?= APPLICATION_NAME ?>:blogMessage", null, "<?= $this->blog ?>"); $(this).addClass("ui-disabled");'>
		    <?= _("Notify me of new messages") ?></a>
		
		<div data-role="collapsible-set" data-theme="d" data-content-theme="d" style="padding-top: 60px;">
			<? $first=true; foreach($this->messages as $k=>$v) : ?>
			<div data-role="collapsible" <? if($first){echo('data-collapsed="false"');$first=false;} ?>>
				<h2><?= $v['title'] ?></h2>
			
				<ul data-role="listview" data-theme="d" data-divider-theme="d">
					<li id="<?= $k ?>">
			
						<div style="position: absolute;">
							<a class="vote-up-off" onclick="rate($(this), '<?= $k ?>', '<?= $v['user'] ?>', 1);" title="<?= $v['up'] ?> up votes (click again to undo)">up vote</a>
							<p style="text-align: center;font-weight: bold;"><?= $v['up']-$v['down'] ?></p>
							<a class="vote-down-off" onclick="rate($(this), '<?= $k ?>', '<?= $v['user'] ?>', 0);" title="<?= $v['down']?> down votes, (click again to undo)">down vote</a>
						</div>
					
						<div style="padding-left: 40px; padding-top: 5px; font-weight: lighter;"><?= $v['text'] ?></div>
						<p class="ui-li-aside"><a href="?action=extendedProfile&user=<?= $v['user'] ?>" data-transition="slidedown" class="user-sig"><?= prettyprintUser($v['user']) ?></a> le <time><?= date('j/n/y G:i', $v['time']) ?></time>
					 		<a onclick="reply($(this));"><span class="highlighted">reply</span></a>
				 			<a title="<?= _("Subscribe") ?>" onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:BlogDetails", "<?= APPLICATION_NAME ?>:blogComment", null, "<?= $this->blog.'comments'.$k ?>");'><span class="highlighted">subscribe</span></a>
				 			<? if($v['user']==$_SESSION['user']->id || $_SESSION['myEurope']->permission > 1) : ?><a href="#deletePopup" data-rel="popup" data-position-to="origin" onclick="setIds($(this));" class="delete-icon" title=""></a><? endif; ?>
						</p>
						<br />
						<br />
						<div data-role="collapsible" data-mini="true" data-inset="false">
							<h2 style="width:165px;"><?= count($this->comments[$k]) ?> <?= _("comments") ?></h2>
							<div id="<?= 'comment'.$k ?>" class='comment' style="margin-bottom: 15px;">
								<textarea name="text" placeholder="add a comment"></textarea>
								<a type="button" data-inline="true" data-mini="true" data-inline="true" onclick="commentAdd($(this));" ><?= _('Reply') ?></a>
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
								<? endforeach; ?>
							</ul>
						</div>
					</li>
				</ul>
			</div>
			<? endforeach ?>
		</div>
		
		<br />
				
		<div data-mini="true" data-inline="true" style="margin-bottom: -.5em;" data-collapsed-icon="edit" data-expanded-icon="faminus">
			<h3 style="margin:auto;margin-left: 0;width:165px;"><?= _('New Message') ?></h3>
			<form method="post" action="?action=Blog&method=create&id=<?= $this->blog ?>"  style="text-align:right;" data-ajax="false">
				<input type="text" name="title" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />				
				<textarea id="CLEeditor" name="text"></textarea>
				<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Post') ?>" />
			</form>
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
		 
	</div>
</div>

<? include("footer.php"); ?>