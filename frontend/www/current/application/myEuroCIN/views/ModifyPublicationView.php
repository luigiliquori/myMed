<!--
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
 -->
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
						<input type="hidden" name="expire_date" value="<?= $this->result->expire_date ?>" />
						<input type="hidden" name="Nazione" value="<?= $this->result->Nazione ?>" />
						<input type="hidden" name="Lingua" value="<?= $this->result->Lingua ?>" />
						<input type="hidden" name="validated" value="<?= $this->result->validated ?>" />
						<input type="hidden" name="data" value="<?= $this->result->getTitle() ?>" />
						<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
						<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
						<? if(isset($this->result->Arte_Cultura)) 
							echo '<input type="hidden" name="Arte_Cultura" value="on" />' ?> 
						<? if( isset($this->result->Natura) ) 
							echo '<input type="hidden" name="Natura" value="on" />' ?> 
						<? if( isset($this->result->Tradizioni) ) 
							echo '<input type="hidden" name="Tradizioni" value="on" />' ?> 
						<? if( isset($this->result->Enogastronomia) ) 
							echo '<input type="hidden" name="Enogastronomia" value="on" />' ?> 
						<? if( isset($this->result->Benessere) ) 
							echo '<input type="hidden" name="Benessere" value="on" />' ?> 
						<? if( isset($this->result->Storia) ) 
							echo '<input type="hidden" name="Storia" value="on" />' ?> 
						<? if( isset($this->result->Religione) ) 
							echo '<input type="hidden" name="Religione" value="on" />' ?> 
						<? if( isset($this->result->Escursioni_Sport) ) 
							echo '<input type="hidden" name="Escursioni_Sport" value="on" />' ?> 
						
						<!-- TITLE -->
						<h3><?= $this->result->getTitle(); ?></h3>
						<p> <? if( isset($this->result->expire_date) ) echo _("Expire date: ").$this->result->expire_date; ?> </p>
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Locality") ?></b>: <?= Categories::$localities[$this->result->Nazione] ?><br/>
							<b><?= _("Language") ?></b>: <?= Categories::$languages[$this->result->Lingua] ?><br/>
							<b><?= _("Categories") ?></b>: 
							<? if( isset($this->result->Arte_Cultura) ) echo _("Art/Cultur "); ?> 
							<? if( isset($this->result->Natura) ) echo _("Nature "); ?>
							<? if( isset($this->result->Tradizioni) ) echo _("Traditions "); ?>
							<? if( isset($this->result->Enogastronomia) ) echo _("Enogastronimy "); ?>
							<? if( isset($this->result->Benessere) ) echo _("Wellness "); ?>
							<? if( isset($this->result->Storia) ) echo _("History "); ?>
							<? if( isset($this->result->Religione) ) echo _("Religion "); ?>
							<? if( isset($this->result->Escursioni_Sport) ) echo _("Sport "); ?>
						</p>
						<br/>
						 
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
