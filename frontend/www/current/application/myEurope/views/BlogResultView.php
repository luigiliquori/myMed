<div data-role="page" id="BlogResult" data-ajax="false">
	<? print_header_bar(true, false); ?>	
	<div data-role="content" data-ajax="false">
	<? include_once 'notifications.php'; ?>
		<br />
		<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="...">
			<li data-role="list-divider">Topics in cathegory <?= $this->cathegory ?></li>		
			<? if (count($this->result) == 0) :?>
				<li>
					<h4>No result found</h4>
				</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
				<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=blogDetails&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3>Tittle: <?= $item->pred2 ?></h3>
						<b>Publication date</b>: <?=  date('Y-m-d', $item->begin) ?><br/>
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
		
		<form action="index.php?action=blog" method="POST" data-ajax="false">
			<input type="hidden" name="pred1" value="<?= $this->cathegory ?>" />
			<input type="text" name="pred2" placeholder="<?= translate('Title') ?>"/>
			
			<script type="text/javascript">
				// Dictionnary of already initliazed pages
				gInitialized = {}
				// Initialization made on each page 
				$('[data-role=page]').live("pagebeforeshow", function() {
				var page = $("BlogResultView");
				var id = page.attr("id");
				// Don't initialize twice
				if (id in gInitialized) return;
				gInitialized[id] = true;
				//debug("init-page : " + page.attr("id"));
				console.log('hello');
				$("#CLEeditor").cleditor({width:500, height:180, useCSS:true})[0].focus();
				});
			</script>
			
			<textarea id="CLEeditor" name="data1"></textarea>
			<input type="hidden" name="method" value="Publish" />
			<center><input type="submit" value="<?= translate('Publish') ?>" data-inline="true" data-icon="check"/></center>			
		</form>		
	</div>
</div>
