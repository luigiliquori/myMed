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
<!-- ------------------------------------ -->
<!-- ExtendedProfileCreateView            -->
<!-- Implements a profile creation wizard --> 
<!-- ------------------------------------ -->


<!-- Page view -->
<div data-role="page" id="createnewprofileview" >
	
	<!-- Header bar -->
	<? 	$title = _("Choose your profile");
		if(strpos($_SERVER['HTTP_REFERER'],"?action=profile")) {
			print_header_bar("back", false, $title);
		} else {
	   		print_header_bar("?action=main", "createprofileHelpPopup", $title); 
	   	}?>
		
	<!-- Page content -->
	<div data-role="content">
		<br>
	    <p style="text-align: center"><?= _("Choose the type of profile you want to create:") ?></p>
		<br>
		 
		<a data-role="button" data-theme="e" class="mm-left" data-ajax="false" href="?action=ExtendedProfile&method=start_wizard&type=Volunteer">
			<?= _("A volunteer") ?>
		</a><br>
		<a data-role="button" data-theme="e" class="mm-left" data-ajax="false" href="?action=ExtendedProfile&method=start_wizard&type=Association">
        	<?= _("An association") ?>
		</a>		
		
	</div>
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="createprofileHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Choose your role.") ?></p>
	</div>
	
</div> <!-- END of Page view -->