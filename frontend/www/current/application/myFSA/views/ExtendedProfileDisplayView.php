<?
 include("header.php"); ?>
 </head>
<body>
<div data-role="page" id="PublishView" data-theme="b">


<? include("header-bar.php"); ?>
<div data-role="content">

			<div class="ui-grid-b">
				<div class="ui-block-a">
					<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ext-profile-photo"/>
					<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>




		<?php if ($_SESSION["profileFilled"] == "company") {?>
							<p class="ui-li-desc"><?=$_SESSION['user']->email?></p>
				</div>
			<div class="ui-block-b">
			<h3 class="ui-li-heading"><?= translate("About you") ?> </h3>		
					<br> <?= _("Company type") ?> : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
					
					<br> <?= _("Company name") ?> :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
					
					<br> <?= _("Company address") ?> : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
					
					<br> <?= _("SIRET") ?> :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
			</div>
		<?php }?>
		<?php if ($_SESSION["profileFilled"] == "employer") {?>
							<p class="ui-li-desc"><?=$_SESSION['user']->email?></p>
				</div>
			<div class="ui-block-b">
			<h3 class="ui-li-heading"> <?= _("About you") ?> </h3>

						
				<!-- 	displaying array:
				
						$object = array( 
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]); -->
			
					<br> <?= translate("Campus") ?> : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
					
					<br> <?= translate("University") ?> :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
					
					<br> <?= translate("Field of Studies") ?> : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
					
					<br> <?= translate("Student number") ?> :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
				</div>
		<?php }?>
		
				<?php if ($_SESSION["profileFilled"] == "guest") {?>
				</div>
			<div class="ui-block-b">
			<h3 class="ui-li-heading"> <?= translate("About you") ?> </h3>

						Vous etes connectes en tant qu'invite.
			</div>
		<?php }?>
					<div class="ui-block-c">
				<!-- langue -->
	<form action="?action=extendedProfile" method="post" data-ajax="false">
		<label for="lang" ><?= _("Language") ?>	: </label>
		<select id="lang" name="lang" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : ""?> >
			<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
			<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
			<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
		</select>
	 <? if(!isset($_SESSION['userFromExternalAuth'])): ?>
			<input type="submit" data-role="button" data-inline="true" data-theme="b" value="<?= _("Update") ?>" />
	 <? endif; ?>
	 <!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
	 <? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
	 		<p style="text-align: center"><br>
				<a type="button" href="?action=UpgradeAccount&method=migrate"  data-theme="g" data-icon="pencil" data-inline="true"><?= _('Create a myMed profile') ?></a>
	 		</p>
	 <? endif; ?>
	</form>
			
			</div>
			</div>
</div>

<? include("footer.php"); ?>
</div>