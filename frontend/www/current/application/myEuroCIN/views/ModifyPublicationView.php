<!-- ---------------------- -->
<!-- ModifyPublication View -->
<!-- ---------------------- -->


<!-- Page view -->
<div data-role="page" id="modifypublicationview">

	<!-- Page header -->
	<? $title = _("Modify publication");
	print_header_bar("?action=details&predicate=".$_GET['predicate']."&author=".$_GET['author'], false, $title); ?>
	
	<!-- Page content -->
	<div data-role="content" >
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			
			<br />	
			<h3><?= _("Edit publication") ?></h3>
				<div>
					<form action="?action=publish&method=update" method="POST" data-ajax="false" >
						<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
						<input type="hidden" name="type" value="<?= $this->result->type ?>" />
						<input type="hidden" name="begin" value="<?= $this->result->begin ?>" />
						<input type="hidden" name="date" value="<?= $this->result->end  ?>" />
						<input type="hidden" name="locality" value="<?= $this->result->locality ?>" />
						<input type="hidden" name="language" value="<?= $this->result->language ?>" />
						<input type="hidden" name="category" value="<?= $this->result->category ?>" />
						<input type="hidden" name="validated" value="<?= $this->result->validated ?>" />
						<input type="hidden" name="title" value="<?= $this->result->title ?>" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						
						<!-- TITLE -->
						<h3><?= _("Title")?>: <?= $this->result->title ?> </h3>
						<p style="position: relative; margin-left: 30px;">
							<b><?= _('Date of expiration') ?></b>: <?= $this->result->end ?><br/><br/>
							<b><?= _("Locality") ?></b>: <?= $this->result->locality ?><br/>
							<b><?= _("Language") ?></b>: <?= $this->result->language ?><br/>
							<b><?= _("Category") ?></b>: <?= $this->result->category ?><br/>
						</p>
						 
						<!-- TEXT -->
						<textarea id="projecttext" name="text"><?= $this->result->text ?></textarea>
						<script type="text/javascript">
							// Init cle editor on pageinit
		  					$("#modifypublicationview").on("pageshow", function() {  
	    						$("#projecttext").cleditor();
	     		 			});
	    				</script>
						<br/>
						
						<!-- MODIFY -->
						<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
							<div style="text-align: center">
								<input type="submit" data-icon="check" data-theme="g" data-inline="true" value="<?= _('Modify publication') ?>"/>
					 		</div>
						<? } ?>
					</form>
				</div>
			</div>
		</div>
		
	</div>
	
	<script type="text/javascript">
    	  $("#modify").on("pageshow", function() {  
    		$("#projecttext").cleditor();
    		
     		 });
    </script>
    
</div>
