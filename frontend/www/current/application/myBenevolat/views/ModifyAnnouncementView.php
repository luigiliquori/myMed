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
<? require_once('notifications.php'); ?>

<!-- Page view -->
<div data-role="page" id="modifyannouncementview">

	<!-- Page header -->
	<? $title = _("Modify announcement");
	print_header_bar("?action=details&id=".$_GET['id'], false, $title); ?>
	<!-- Page content -->
	<div data-role="content" >
		<? print_notification($this->success.$this->error); ?>
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
			<div data-role="collapsible" data-collapsed="false">
				<br />	
				<h3><?= _("Edit announcement") ?></h3>				
				<div>
					<form action="?action=publish&method=update" method="POST" data-ajax="false">
						<input type="hidden" name="publisher" value="<?= $this->result->publisherID ?>" />
						<input type="hidden" name="id" value="<?= $this->result->id ?>" />
						<input type="hidden" name="title" value="<?= $this->result->title ?>" />
						<input type="hidden" name="begin" value="<?= $this->result->begin ?>" />
						<input type="hidden" name="promue" value="<?= $this->result->promue ?>" />
						<input type="hidden" name="validated" value="<?= $this->result->validated ?>" />
						<input type="hidden" id="date" name="date" value="" />
									
						<!-- TITLE -->
						<h3><?= _("Title")?> : <?= $this->result->title ?></h3>
						<!--<input id="postTitle" class="postTitle" data-inline="true" name="title" value='<?= $this->result->title ?>' type="text" />-->
	
		    			<h3><?= _('Date de fin') ?>* :</h3>
		    			<?
						$timestamp = strtotime($this->result->end); 
						$d = date('d', $timestamp);
						$m = date('m', $timestamp);
		  				$y = date('Y', $timestamp);
		  				?>
						<fieldset data-role="controlgroup" data-type="horizontal"> 
							<select id="expire_day_content" name="expire_day" data-inline="true">
								<option value=""><?= _("Day")?></option>
						  	<?  for ($i = 1; $i <= 31; $i++) { 
									if($i==$d){ ?>
										<option selected value="<?= $i ?>"><?= $i ?></option>
								<?  }else{ ?>
										<option value="<?= $i ?>"><?= $i ?></option>
								<? 	}
								} ?>
							</select>
							<select id="expire_month_content" name="expire_month" data-inline="true">
								<option value=""><?= _("Month")?></option>
							<?php for ($i = 1; $i <= 12; $i++) {
									if($i==$m){ ?>
										<option selected value="<?= $i ?>"><?= $i ?></option>
								<?  }else{ ?>
										<option value="<?= $i ?>"><?= $i ?></option>
								<? 	}
								} ?>
							</select>
							<select id="expire_year_content" name="expire_year" data-inline="true">
								<option value=""><?= _("Year")?></option>
							<?  for ($i = 2013; $i <= 2020; $i++) { 
									if($i==$y){ ?>
										<option selected value="<?= $i ?>"><?= $i ?></option>
								<?  }else{ ?>
										<option value="<?= $i ?>"><?= $i ?></option>
								<? 	}
								} ?>
							</select>
						</fieldset>
					
						<h3><?= _('Description') ?>* :</h3>
						<textarea id="postText" name="text"><?= $this->result->text ?></textarea>
						<script type="text/javascript">
		  					$("#modifyannouncementview").on("pageshow", function() {  
	    						$("#postText").cleditor();
	     		 			});
	    				</script>
	    					
						<h3><?= _("Compétences requises (de 1 à 4)")?>* :</h3>
						<?  foreach (Categories::$competences as $k=>$v) :
								if(gettype($this->result->competences)=="string" && $this->result->competences==$k){ ?>
									<input type="checkbox" checked name="competences[]" id="<?= $k?>" value="<?= $k ?>"><label for="<?= $k?>"><?= $v ?></label>						
							 <? }else if(gettype($this->result->competences)=="array" && in_array($k, $this->result->competences)){ ?>
									<input type="checkbox" checked name="competences[]" id="<?= $k?>" value="<?= $k ?>"><label for="<?= $k?>"><?= $v ?></label>
							 <? }else{?>
									<input type="checkbox" name="competences[]" id="<?= $k?>" value="<?= $k ?>"><label for="<?= $k?>"><?= $v ?></label>
							  <?}
						 	endforeach ?>
					
					
						<h3><?= _("Informations pratiques") ?>* :</h3>
						<select name="quartier" id="postQuartier" data-native-menu="false">
							<option value=""> <?= _("Quartier")?> </option>
							<?  foreach (Categories::$mobilite as $k=>$v) :
									if($k==$this->result->quartier){?>
										<option selected value="<?= $k ?>"><?= $v ?></option>
								<?  }else{ ?>
										<option value="<?= $k ?>"><?= $v ?></option>
								<? 	} 
								endforeach ?>
						</select>
						<select name="mission" id="typeMission" data-native-menu="false">
							<option value=""> <?= _("Type de mission")?> </option>
							<?  foreach (Categories::$missions as $k=>$v) :
									if($k==$this->result->typeMission){?>
										<option selected value="<?= $k ?>"><?= $v ?></option>
								<?  }else{ ?>
										<option value="<?= $k ?>"><?= $v ?></option>
								<? 	} 
								endforeach ?>
						</select>
						<div style="text-align: center">
							<input type="submit" data-icon="check" data-inline="true" data-theme="g" value="<?= _('Modify') ?>" onclick="
								$('#date').val($('#expire_day_content').val() + '-' + $('#expire_month_content').val() + '-' +  $('#expire_year_content').val());"/>
						</div>
					 </form>
				</div>
			</div>
		</div>
	</div>
</div>
