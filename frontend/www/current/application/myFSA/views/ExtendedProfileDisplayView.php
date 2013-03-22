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
<?
 include("header.php"); ?>
 </head>
<body>
<div data-role="page" id="extendedProfileView" data-theme="b">

<div data-role="header" data-theme="b" data-position="fixed">
	<h1 style="color: white;"><?= _("Profile") ?></h1>
	<a href="?action=main" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
	<? include_once "notifications.php"; ?>
</div>
<div data-role="content">
	<? print_notification($this->success.$this->error); ?>
	<br><br>
	<!-- Show user profile -->
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
			
			<!-- User details -->
			<li data-role="list-divider"><?= _("User details") ?></li>	
			<li>
				<div class="ui-grid-b" style="margin-top: 7px;margin-bottom:7px">	
					<div class="ui-block-a" style="width: 130px;">
						<img src="<?=$_SESSION['user']->profilePicture?>" style="width: 110px; vertical-align: middle; padding-right: 10px;"/>
					</div>
					<div class="ui-block-b" style="width: 220px;">
						<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
		 		 	 <? if ($_SESSION["profileFilled"] != "guest") {?>
		 					<p class="ui-li-desc"><?=$_SESSION['user']->email?></p>
				  	 <? } ?>
					</div>
					<div class="ui-block-c">
						<form action="?action=extendedProfile" method="post" data-ajax="false">
							<select id="lang" name="lang" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : ""?> data-inline="true" data-theme="b">
								<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
								<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
								<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
							</select>
							<script>
							$("#lang").change(function() {
							     this.form.submit();
							});
							</script>	
						</form>
					</div>
				</div>
			</li>
			<li data-role="list-divider"><?= _($_SESSION["profileFilled"]. " details") ?></li>	
			<li>
			 <? if ($_SESSION["profileFilled"] == "company") {?>
					<p>
						<br> <?= _("Company type") ?> : 
						<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
							
						<br><br> <?= _("Company name") ?> :
						<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
							
						<br><br> <?= _("Company address") ?> : 
						<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
							
						<br><br> <?= _("SIRET") ?> :
						<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
					</p>
			 <? } ?>
			 <? if ($_SESSION["profileFilled"] == "employer") {?>
					<p>
						<br> <?= translate("Campus") ?> : 
						<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
						
						<br><br> <?= translate("University") ?> :
						<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
						
						<br><br> <?= translate("Field of Studies") ?> : 
						<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
						
						<br><br> <?= translate("Student number") ?> :
						<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
					</p>
			<? } ?>
			<? if ($_SESSION["profileFilled"] == "guest") {?>
					<p>
						<h3 class="ui-li-heading"> <?= translate("About you") ?> </h3>
						Vous etes connectes en tant qu'invite.
					</p>
			<? }?>
			</li>
		</ul>
		<div style="text-align: center;">
		<!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
 		 <? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
				<a type="button" href="?action=UpgradeAccount&method=migrate" data-theme="g" data-icon="pencil" data-inline="true"><?= _('Create a myMed profile') ?></a>
 		 <? endif; ?>
 		 </div>
	</div>
</div>
</body>
