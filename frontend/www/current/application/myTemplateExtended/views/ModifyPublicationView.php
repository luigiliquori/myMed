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
					<!-- TITLE -->
					<h3><?= _("Title")?>: <?= $this->result->title ?> </h3>
					<p style="position: relative; margin-left: 30px;">
						<b><?= _('Deadline') ?></b>: <?= $this->result->end ?><br/><br/>
						<b><?= _("Area") ?></b>: <?= $this->result->area ?><br/>
						<b><?= _("Category") ?></b>: <?= $this->result->category ?><br/>
						<b><?= _("Organization") ?></b>: <?= $this->result->organization ?><br/>
					</p>
					 
					<!-- TEXT -->
					<textarea id="projecttext" name="projecttext"><?= $this->result->text ?></textarea>
					<script type="text/javascript">
						// Init cle editor on pageinit
	  					$("#modifypublicationview").on("pageshow", function() {  
    						$("#projecttext").cleditor();
     		 			});
    				</script>
					<br>
					<!-- MODIFY -->
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="?action=publish&method=update" method="POST" data-ajax="false" >
							<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
							<input type="hidden" name="type" value="<?= $this->result->type ?>" />
							<input type="hidden" name="end" value="<?= $this->result->end  ?>" />
							<input type="hidden" name="area" value="<?= $this->result->area ?>" />
							<input type="hidden" name="category" value="<?= $this->result->category ?>" />
							<?php if ($this->result->category == 'Course'):?>
								<input type="hidden" name="maxappliers" value="<?= $this->result->maxappliers ?>" />
								<input type="hidden" name="currentappliers" value="<?= $this->result->currentappliers ?>" />
							<? endif; ?>
							<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
							<input type="hidden" name="title" value="<?= $this->result->title ?>" />
							<input type="hidden" name="text" id="text"/>
							<input type="submit" data-icon="check" data-theme="g" data-inline="true" data-mini="true" value="<?= _('Modify publication') ?>" onclick="$('#text').val($('#projecttext').val());"/>
			 			</form>
					<? } ?>
					
					<!-- DELETE -->
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="?action=publish&method=delete" method="POST" data-ajax="false">
							<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
							<input type="hidden" name="type" value="<?= $this->result->type ?>" />
							<input type="hidden" name="end" value="<?= $this->result->end  ?>" />
							<input type="hidden" name="area" value="<?= $this->result->area ?>" />
							<input type="hidden" name="category" value="<?= $this->result->category ?>" />
							<input type="hidden" name="organization" value="<?= $this->result->organization ?>" />
							<input type="hidden" name="title" value="<?= $this->result->title ?>" />
							<input type="hidden" name="text" id="text"/>
							<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
							<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
							<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= _('Delete publication') ?>" />
			 			</form>
					<? } ?>  
						<br><br>
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
