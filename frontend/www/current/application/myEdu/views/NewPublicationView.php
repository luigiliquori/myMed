<!-- ------------------- -->
<!-- NewPublication view -->
<!-- ------------------- -->

<? require_once('notifications.php'); ?>

<div data-role="page" id="newpublicationview">
	    	
	<? require_once('Categories.class.php'); ?>  
  	
  	<!-- Page header -->
  	<? $title = _("Create publication");	
	   print_header_bar("?action=publish&method=show_user_publications", "helpPopup", $title); ?>

	<!-- Page content -->
	<div data-role="content">
    	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Submit a new publication form -->
		<form id="newpublicationform" action="index.php?action=publish&method=create" method="POST" data-ajax="false">
		
			<input type="hidden" id="area" name="area" value="" />
			<input type="hidden" id="category" name="category" value="" />
			<input type="hidden" id="locality" name="locality" value="" />
			<input type="hidden" id="organization" name="organization" value="" />
			<input type="hidden" id="date" name="date" value="" />
	
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("<<<< TITLE >>>>") ?></h3>
				<?= _("Create a new publication here.")?>
				</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Publish your project') ?> :</h3>
				
				<h3><?= _('Title') ?> : </h3>
				<input id="textinputp3" class="postTitle" data-inline="true" name="title" value='' type="text" />
				
				<h3><?= _('Date of expiration') ?> :</h3>
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
					<?php for ($i = 2012; $i <= 2042; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
				</fieldset>
					
				<h3><?= _('Enter a text description') ?> :</h3>
				<textarea id="text" name="text"></textarea>
				<script type="text/javascript">
					// Init cle editor on pageinit
	  				$("#newpublicationview").on("pageshow", function() {  
    					$("#text").cleditor();
     		 		});
    			</script>
				
				<br />
				
				<h3><?= _('Other criteria') ?> :</h3>
					<select name="area" id="area" data-native-menu="false">
					<option value=""> <?= _("Area")?> </option>
					<? foreach (Categories::$areas as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
					</select>
					<select name="category" id="category" data-native-menu="false" 
							onChange=" 
								if($('select#category').val() == 'Course') {
									$('#maxappliersdiv').show();
								} else {
									$('#maxappliersdiv').hide();
								}
								
					" >
						<option value=""> <?= _("Category")?> </option>
					<? foreach (Categories::$categories as $k=>$v) :?>
						<?= ($k == 'Course' &&  
							!($_SESSION['myEdu']->details['role']=='professor')) ? '' : 
						'<option value="'.$k.'">'.$v.'</option>';
						?>
					<? endforeach ?>
					</select>
					<div data-role="fieldcontain" id="maxappliersdiv" name="maxappliersdiv" style="text-align:right; display: none; margin-right:30px;">
						<label for="maxappliers" ><?=_(" Max course appliers number: "); ?></label>
	    				<input type="text" name="maxappliers" id="maxappliers" value="30" style="width:80px; text-align:right;"/>
					</div>
					<input type="hidden" id="currentappliers" name="currentappliers" value="-1" />
					<select name="locality" id="locality" data-native-menu="false">
						<option value=""> <?= _("Locality")?> </option>
					<? foreach (Categories::$localities as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
					</select>
					<select name="organization" id="organization" data-native-menu="false">
						<option value=""> <?= _("Organization")?> </option>
					<? foreach (Categories::$organizations as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
					</select>
				
			</div>
			
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-icon="check" data-theme="g" value="<?=_('Publish') ?>" onclick="
					$('#date').val($('#publish_day_content').val() + '-' + $('#publish_month_content').val() + '-' +  $('#publish_year_content').val());					
				"/>
			</div>
	
		</form>
	</div>
		
		
	<!-- Help popup -->
	<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<?= _("Fill out all of the parameters, such as title, expiration date, description, specialization, category, locality and company.")?> 
	</div>
	
</div>

