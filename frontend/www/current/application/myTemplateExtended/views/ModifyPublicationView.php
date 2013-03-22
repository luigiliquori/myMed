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
					<?if ($this->result->publisherID == $_SESSION['user']->id){ ?>
						<form action="?action=publish&method=update" method="POST" data-ajax="false" >
							<input type="hidden" name="author" value="<?= $_SESSION['user']->id ?>" />
							<input type="hidden" name="predicate" value="<?= $this->result->getPredicateStr() ?>" />
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
				
							<!-- TITLE -->
							<h3><?= _("Title")?>: <?= $this->result->title ?> </h3>
							<p style="position: relative; margin-left: 30px;">
								<b><?= _('Deadline') ?></b>: <?= $this->result->end ?><br/><br/>
								<b><?= _("Area") ?></b>: <?= Categories::$areas[$this->result->area] ?><br/>
								<b><?= _("Category") ?></b>: <?= Categories::$categories[$this->result->category] ?><br/>
								<b><?= _("Organization") ?></b>: <?= Categories::$organizations[$this->result->organization] ?><br/><br/>
							</p>
							 
							<!-- TEXT -->
							<textarea id="projecttext" name="text"><?= $this->result->text ?></textarea>
							<script type="text/javascript">
								// Init cle editor on pageinit
			  					$("#modifypublicationview").on("pageshow", function() {  
		    						$("#projecttext").cleditor();
		     		 			});
		    				</script>
							<br>

							<div style="text-align: center">
								<input type="submit" data-icon="check" data-theme="g" data-inline="true" value="<?= _('Modify publication') ?>"/>
						 	</div>
						</form>	
					<? } ?>
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
