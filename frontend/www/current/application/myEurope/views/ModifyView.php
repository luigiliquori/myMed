<div data-role="page" id="modify">

	<? print_header_bar(true, false); ?>
	
	<div data-role="content" >
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			
			<br />	
			<h3><?= _("Edit project") ?></h3>
								
	    		<!-- REPUTATION -->
	    		<div style="position: absolute; top:110px; right: 25px;">
					<a href="#popupReputationProject" data-rel="popup">
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							<?php if($i*20-20 < $this->reputation["value"] ) { ?>
								<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
							<?php } else { ?>
								<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
							<?php } ?>
						<? } ?>
					</a>
				</div>
				<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Do you like the project idea ?") ?><br /><br />
					<form action="#" method="get" data-ajax="false">
						<input type="hidden" name="action" value="updateReputation" />
						<input type="hidden" name="isData" value="1" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						<input type="range" name="reputation" id="reputation" value="5" min="0" max="10" data-mini="true"/>
						<input type="submit" value="Send" data-mini="true" data-theme="g"/>
					</form>
				</div>
				<!-- END REPUTATION -->
	    		
				<div>
					<!-- TITLE -->
					<h3>Project title: <?= $this->result->title ?> </h3>
					
					<!-- TEXT -->
					<textarea id="projecttext" name="projecttext"><?= $this->result->text ?></textarea>
					<script type="text/javascript">
						// Init cle editor on pageinit
	  					$("#modify").on("pageshow", function() {  
    						$("#projecttext").cleditor();
     		 			});
    				</script>
					
					<!-- CONTACT -->			
					<p><b>Contact</b>: <?= str_replace("MYMED_", "", $this->result->publisherID) ?><br/></p>
					
					<!-- Keywords an timeline
					<p style="position: relative; margin-left: 30px;">
						<b><?= _('keywords') ?></b>: <?= $this->result->theme ?>, <?= $this->result->other ?><br/>
						<b>End</b>: <?= $this->result->end ?><br/><br />
					</p> -->
					
					<!-- MODIFY -->
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="?action=main" method="POST" data-ajax="false" >
							<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
							<input type="hidden" name="type" value="<?= $this->result->type ?>" />
							<input type="hidden" name="theme" value="<?= $this->result->theme ?>" />
							<input type="hidden" name="title" value="<?= $this->result->title ?>" />
							<input type="hidden" name="other" value="<?= $this->result->other ?>" />
							<input type="hidden" name="text" id="text"/>
			 				<input type="hidden" name="method" value="Publish" />
							<input type="submit" data-icon="check" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Modify publication') ?>" onclick="$('#text').val($('#projecttext').val());"/>
			 			</form>
					<? } ?>
					
					<!-- DELETE -->
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="?action=main" method="POST" data-ajax="false">
							<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
							<input type="hidden" name="type" value="<?= $this->result->type ?>" />
							<input type="hidden" name="theme" value="<?= $this->result->theme ?>" />
							<input type="hidden" name="title" value="<?= $this->result->title ?>" />
							<input type="hidden" name="other" value="<?= $this->result->other ?>" />
			 				<input type="hidden" name="method" value="Delete" />
							<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= _('Delete publication') ?>" />
			 			</form>
					<? } ?>  
					
				</div>
				
				<!-- SHARE THIS -->
				<div style="position: absolute; right: 24px;">
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="my_Europe" data-url="<?= str_replace('@','%40','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>">Tweet</a>
    				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
					
					<div style="position: absolute; right: 95px;">
				<g:plusone size="tall"></g:plusone>
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'en';}</script>
				</div>
				
				<div style="position: absolute; right: 150px;">
				<a name="fb_share" type="box_count" share_url="<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" ></a>
   				<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
				</div>
				<div style="height: 80px;"></div>
	    		<!-- END SHARE THIS -->
				
			</div>
		
		</div>
		
	</div>
	
	<script type="text/javascript">
    	  $("#modify").on("pageshow", function() {  
    		$("#projecttext").cleditor();
    		
     		 });
    </script>
    
</div>