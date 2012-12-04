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
				<ul data-role="listview" >
		
			<li data-role="list-divider">Results</li>
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4>No result found</h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3>Wrapped: <?= $item->wrapped1 ?> - <?= $item->wrapped2 ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b>Pred1</b>: <?= $item->pred1 ?><br/>
							<b>Pred2</b>: <?= $item->pred2 ?><br/>
							<b>Pred3</b>: <?= $item->pred3 ?><br/><br/>
							<b>Begin</b>: <?= $item->begin ?><br/>
							<b>End</b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							reputation: 
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputationMap[$item->publisherID] ) { ?>
									<img alt="rep" src="img/yellowStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;" />
								<?php } else { ?>
									<img alt="rep" src="img/grayStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;"/>
								<?php } ?>
							<? } ?>
						</p>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		</div>
		
		<br />				
		<div data-mini="true" data-inline="true" style="margin-bottom: -.5em;" data-collapsed-icon="edit" data-expanded-icon="faminus">
			<h3 style="margin:auto;margin-left: 0;width:165px;"><?= _('New Message') ?></h3>
			<form method="post" action="?action=Blog" data-ajax="false" style="text-align:right;">
				<input type="text" name="title" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />				
				<textarea id="CLEeditor" name="text"></textarea>
				<input type="submit" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Publish') ?>" />
			</form>
		</div>
		 
	</div>
</div>

<? include("footer.php"); ?>		
