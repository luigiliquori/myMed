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
				$("#CLEeditor").cleditor( {width:500, height:180, useCSS:true})[0].focus();} );
			</script>	
	
	<div data-role="content" data-ajax="false">
		
		<a type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign" style="float: right;
		    "onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:BlogDetails", "<?= APPLICATION_NAME ?>:blogMessage", null, "<?= $this->blog ?>"); $(this).addClass("ui-disabled");'>
		    <?= _("Notify me of new messages") ?></a>
		
		
		<script type="text/javascript">

		$( ".selector" ).collapsible({
			   expand: function(event, ui) { alert('dzialam!'); }
			});​
		$( ".selector" ).collapsible({
			   collapse: function(event, ui) { alert('dzialam!'); }
			});

		</script>
					
			<? foreach($this->result as $item) : ?>	
			<div data-role="collapsible" data-theme="d" data-collapsed="true" id="number1">
						<h3>Topic: <?= $item->pred2 ?>
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputationMap[$item->publisherID] ) { ?>
									<img alt="rep" src="img/yellowStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 600px; margin-top:3px;" />
								<?php } else { ?>
									<img alt="rep" src="img/grayStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 600px; margin-top:3px;"/>
								<?php } ?>
							<? } ?>
							</h3>
							<p>dghejfgheru</p>
			</div>
			<? endforeach ?>
			

		
		<br />				
		<div data-role="collapsible" data-collapsed-icon="edit" data-expanded-icon="faminus" id="my-collaspible">
			<h3 style="margin:auto;margin-left: 0;width:165px;"><?= _('New Message') ?></h3>
			
			<form action="?action=blog" method="POST" data-ajax="false" style="text-align:right;">
				<input type="hidden" name="pred1" value="<?= $_SESSION['pred1'] ?>" />
				<input type="text" name="pred2" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />				
				<textarea id="CLEeditor" name="data1"></textarea>
				<input type="submit" name="method" data-theme="b" data-mini="true" data-inline="true" value="<?= _('Publish') ?>" />
			</form>
			
		</div>
		 
	</div>
</div>

<? include("footer.php"); ?>		
