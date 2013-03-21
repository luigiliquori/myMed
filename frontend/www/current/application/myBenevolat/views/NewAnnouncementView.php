<!-- ------------------------ -->
<!-- NewPublication view      -->
<!-- Create a new publication --> 
<!-- ------------------------ -->


<!-- Notification pop up -->
<? require_once('notifications.php'); ?>


<!-- Page view -->
<div data-role="page" id="newannouncementview">
 	
  	<!-- Page header -->
  	<? $title = _("Create announcement");	
	   print_header_bar("?action=publish&method=show_user_announcements", "defaultHelpPopup", $title); ?>

	   
	<!-- Page content -->
	<div data-role="content">
    	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Submit a new publication form -->
		<form id="newannouncementform" action="index.php?action=publish&method=create" method="POST" data-ajax="false">
			<input type="hidden" id="date" name="date" value="" />
			<input type="hidden" id="permission" name="permission" value="<?= $_SESSION['myBenevolat']->permission?>" />
			<input type="hidden" name="publisher" value="<?= $_SESSION['user']->id ?>" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("New announcement capsule title") ?> ?</h3>
				<?= _("New announcement capsule text")?>
				</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Publish') ?> :</h3>
				
				<h3><?= _('Title') ?><b>*</b> :</h3>
				<input id="textinputp3" class="postTitle" data-inline="true" name="title" value='' type="text" />
				
				<h3><?= _('Deadline') ?><b>*</b> :</h3>
				<fieldset data-role="controlgroup" data-type="horizontal"> 
					<select id="expire_day_content" name="expire_day" data-inline="true">
						<option value=""><?= _("Day")?></option>
					<?php for ($i = 1; $i <= 31; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<select id="expire_month_content" name="expire_month" data-inline="true">
						<option value=""><?= _("Month")?></option>
					<?php for ($i = 1; $i <= 12; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<select id="expire_year_content" name="expire_year" data-inline="true">
						<option value=""><?= _("Year")?></option>
					<?php for ($i = 2013; $i <= 2020; $i++) { ?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
				</fieldset>
				
				<h3><?= _('Description') ?><b>*</b> :</h3>
				<textarea id="text" name="text"></textarea>
				<script type="text/javascript">
	  				$("#newannouncementview").on("pageshow", function() {  
    					$("#text").cleditor();
     		 		});
    			</script>
					
				<h3><?= _("Skills required (from 1 to 4)")?><b>*</b> :</h3>
				<? foreach (Categories::$competences as $k=>$v) :?>
					<input type="checkbox" name="competences[]" id="<?= $k?>" value="<?= $k ?>"><label for="<?= $k?>"><?= $v ?></label>
				<? endforeach ?>
				
				
				<h3><?= _("Practical information") ?> :</h3>
				
				<select name="quartier" id="quartier" data-native-menu="false" data-overlay-theme="d">
					<option value=""> <?= _("District")?><b>*</b></option>
					<? foreach (Categories::$mobilite as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
				</select>
				<select name="mission" id="mission" data-native-menu="false" data-overlay-theme="d">
					<option value=""> <?= _("Mission type")?><b>*</b></option>
					<? foreach (Categories::$missions as $k=>$v) :?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach ?>
				</select>
				<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			</div>
			
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-icon="check" data-theme="g" value="<?=_('Publish') ?>" onclick="
					$('#date').val($('#expire_day_content').val() + '-' + $('#expire_month_content').val() + '-' +  $('#expire_year_content').val());					
				"/>
			</div>
	
		</form>
	</div>
		
		
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<?= _("New announcement help text")?> 
	</div>
	
</div>

