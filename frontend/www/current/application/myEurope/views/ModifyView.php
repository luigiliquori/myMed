<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<div data-role="page" id="modify">

	<? $title = _("EditProject");
	   print_header_bar("?action=ListUserProjects", false, $title); ?>
	
	<div data-role="content" >
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			
			<br />	
			<h3><?= _("Edit project") ?></h3>
								
				<div>
					<!-- TITLE -->
					<h3><?= _("Project title")?>: <?= $this->result->title ?> </h3>
					
					<!-- TEXT -->
					<textarea id="projecttext" name="projecttext"><?= $this->result->text ?></textarea>
					<script type="text/javascript">
						// Init cle editor on pageinit
	  					$("#modify").on("pageshow", function() {  
    						$("#projecttext").cleditor();
     		 			});
    				</script>
					<br>
					<!-- MODIFY -->
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="?action=publish" method="POST" data-ajax="false" >
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
