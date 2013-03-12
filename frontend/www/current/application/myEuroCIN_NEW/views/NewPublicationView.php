<!-- ------------------------ -->
<!-- NewPublication view      -->
<!-- Create a new publication --> 
<!-- ------------------------ -->


<!-- Notification pop up -->
<? require_once('notifications.php'); ?>


<!-- Page view -->
<div data-role="page" id="newpublicationview">
	    	
	<? require_once('Categories.class.php'); ?>  
  	
  	
  	<!-- Page header -->
  	<? $title = _("Create publication");	
	   print_header_bar("?action=publish&method=show_user_publications", "defaultHelpPopup", $title); ?>

	   
	<!-- Page content -->
	<div data-role="content">
    	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Submit a new publication form -->
		<form id="newpublicationform" action="index.php?action=publish&method=create" method="POST" data-ajax="false">
		
			<input type="hidden" id="date" name="date" value="" />
	
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("How to publish") ?> ?</h3>
				<?= _("Create publication capsule text")?>
				</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Publish your project') ?> :</h3>
				
				<h3><?= _('Title') ?><b>*</b> : </h3>
				<input id="textinputp3" class="postTitle" data-inline="true" name="data"
					placeholder="<?= _("Publication title goes here") ?>" value='' type="text" />
				
				<h3><?= _('Deadline') ?><b>*</b> :</h3>
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
					
				<h3><?= _('Description') ?><b>*</b> :</h3>
				<textarea id="text" name="text"></textarea>
				<script type="text/javascript">
					// Init cle editor on pageinit
	  				$("#newpublicationview").on("pageshow", function() {  
    					$("#text").cleditor();
     		 		});
    			</script>
				
				<br />
				
				<h3><?= _('Other information') ?> :</h3>
					<select name="Nazione" id="Nazione" data-native-menu="false">
					<option value=""> <?= _("Locality")?><b>*</b> </option>
					<? foreach (Categories::$localities as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
					</select>
					<select name="Lingua" id="Lingua" data-native-menu="false">
						<option value=""> <?= _("Language")?><b>*</b> </option>
					<? foreach (Categories::$languages as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
					</select><br />
					<!-- Categories -->
					<label for="categories"><strong><?= _("Categories") ?></strong></label>
					<fieldset data-role="controlgroup" id="categories">
					<? foreach (Categories::$categories as $k=>$v) :?>
					   	<input type="checkbox" name="<?= $k ?>" id="<?= $k ?>" class="custom" value="on">
					   	<label for="<?= $k ?>"><?= $v ?></label>
	   				<? endforeach ?>			   
				    </fieldset>
					<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			</div>
			
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-icon="check" data-theme="g" value="<?=_('Publish') ?>" onclick="
					$('#date').val($('#publish_day_content').val() + '-' + $('#publish_month_content').val() + '-' +  $('#publish_year_content').val());					
				"/>
			</div>
	
		</form>
	</div>
		
		
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<?= _("<<<<< Help text >>>>>")?> 
	</div>
	
</div>

