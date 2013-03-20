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
<!-- ------------------- -->
<!-- NewPublication view -->
<!-- ------------------- -->

<? require_once('notifications.php'); ?>

<div data-role="page" id="newpublicationview">
	    	
	<? require_once('Categories.class.php'); ?>  
  	
  	<!-- Page header -->
  	<? $title = _("Offer's creation");	
	   print_header_bar("?action=publish&method=show_user_publications", "helpPopup", $title); ?>

	<!-- Page content -->
	<div data-role="content">
    	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Submit a new publication form -->
		<form id="newpublicationform" action="index.php?action=publish&method=create" method="POST" data-ajax="false">
			<input type="hidden" name="author" value="<?= $_SESSION['user']->id ?>" />
			<input type="hidden" id="area" name="area" value="" />
			<input type="hidden" id="category" name="category" value="" />
			<input type="hidden" id="locality" name="locality" value="" />
			<input type="hidden" id="organization" name="organization" value="" />
			<input type="hidden" id="end" name="end" value="" />
	
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("Offer's creation") ?></h3>
				<?= _("Create your offer by filling all the mandatory fields.")?>
				</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Publish your offer') ?> :</h3>
				
				<h3><?= _('Title') ?><b>*</b> : </h3>
				<input id="textinputp3" class="postTitle" data-inline="true" name="title" value='' type="text" />
				
				<h3><?= _('Deadline')?><?if($_SESSION['myEdu']->details['role']=='professor') echo "<b>**</b>"?> :</h3> 
				<fieldset data-role="controlgroup" data-type="horizontal"> 
					<select id="publish_day_content" name="expire_day" data-inline="true">
						<option value=""><?= _("Day")?></option>
					<?php for ($i = 1; $i <= 31; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<select id="publish_month_content" name="expire_month" data-inline="true">
						<option value=""><?= _("Month")?></option>
					<?php for ($i = 1; $i <= 12; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<select id="publish_year_content" name="expire_year" data-inline="true">
						<option value=""><?= _("Year")?></option>
					<?php for ($i = 2013; $i <= 2020; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
				</fieldset>
					
				<h3><?= _('Description') ?><b>*</b> :</h3>
				<textarea id="text" name="text"></textarea>
				<script type="text/javascript">
					// Init cle editor on pageinit
	  				$("#newpublicationview").on("pageshow", function() {  
    					$("#text").cleditor();
     		 		});
    			</script>
				
				<br />
				
				<h3><?= _('Other criteria') ?> :</h3>
					<select name="category" id="category" data-native-menu="false" data-overlay-theme="d" 
							onChange=" 
								if($('select#category').val() == 'Course') {
									$('#maxappliersdiv').show();
								} else {
									$('#maxappliersdiv').hide();
								}
					" >
						<option value=""> <?= _("Category")?><b>*</b></option>
					<? foreach (Categories::$categories as $k=>$v) :?>
						<?= ($k == 'Course' &&  
							!($_SESSION['myEdu']->details['role']=='professor')) ? '' : 
						'<option value="'.$k.'">'.$v.'</option>';
						?>
					<? endforeach ?>
					</select>
					<div data-role="fieldcontain" id="maxappliersdiv" name="maxappliersdiv" style="text-align:right; display: none; margin-right:20px;">
						<label for="maxappliers"><?=_("Max course appliers number") ?><b>*</b> :</label>
	    				<input type="text" name="maxappliers" id="maxappliers" value="30" style="width:80px; text-align:right;"/>
					</div>
					<input type="hidden" id="currentappliers" name="currentappliers" value="-1" />
					<select name="organization" id="organization" data-native-menu="false" data-overlay-theme="d">
						<option value=""> <?= _("Institution")?><b>*</b></option>
						<? foreach (Categories::$organizations as $k=>$v) :?>
							<option value="<?= $k ?>"><?= $v ?></option>
						<? endforeach ?>
					</select>
					<select name="locality" id="locality" data-native-menu="false" data-overlay-theme="d">
						<option value=""> <?= _("Locality")?><b>*</b></option>
					<? foreach (Categories::$localities as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
					</select>
					
					<select name="area" id="area" data-native-menu="false" data-overlay-theme="d">
						<option value=""> <?= _("Topic")?><b>*</b></option>
					<?  foreach(Categories::$areas as $k=>$v) :?>
							<optgroup label="<?= _($k) ?>">
							<?  foreach($v as $item) :?>
									<option value="<?= $item ?>"><?= _($item) ?></option>
							<?  endforeach;?>
							</optgroup>
					<?  endforeach ?>
					</select>
					
					<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
					<?if($_SESSION['myEdu']->details['role']=='professor'):?>
						<p><b>**</b>: <i><?= _("Mandatory if you publish a course")?></i></p>
					<?endif;?>
			</div>
			
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-icon="check" data-theme="g" value="<?=_('Publish') ?>" onclick="
					$('#end').val($('#publish_day_content').val() + '-' + $('#publish_month_content').val() + '-' +  $('#publish_year_content').val());					
				"/>
			</div>
	
		</form>
	</div>
		
		
	<!-- Help popup -->
	<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<?= _("Create your offer by filling all the mandatory fields.")?> 
	</div>
	
</div>

