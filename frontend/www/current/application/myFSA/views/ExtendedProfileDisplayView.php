<?
 include("header.php"); ?>
<div data-role="page" id="PublishView" data-theme="b">


<? include("header-bar.php"); ?>
<div data-role="content">

			<div class="ui-grid-b">
				<div class="ui-block-a">
					<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ext-profile-photo"/>
					<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>




		<?php if ($_SESSION["profileFilled"] == "company") {?>
							<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
				</div>
			<div class="ui-block-b">
			<h3 class="ui-li-heading"><?= _("About you") ?> </h3>		
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
							<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
				</div>
			<div class="ui-block-b">
			<h3 class="ui-li-heading"> <?= _("About you") ?> </h3>

						
				<!-- 	displaying array:
				
						$object = array( 
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]); -->
			
					<br> <?= _("Campus") ?> : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
					
					<br> <?= _("University") ?> :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
					
					<br> <?= _("Field of Studies") ?> : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
					
					<br> <?= _("Student number") ?> :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
				</div>
		<?php }?>
		
				<?php if ($_SESSION["profileFilled"] == "guest") {?>
				</div>
			<div class="ui-block-b">
			<h3 class="ui-li-heading"> <?= _("About you") ?> </h3>

						Vous etes connectes en tant qu'invite.
			</div>
		<?php }?>
					<div class="ui-block-c">
				<!-- langue -->
	<form action="?action=extendedProfile" method="post" data-ajax="false">
		<label for="lang" ><?= _("Language") ?>	: </label>
		<select id="lang" name="lang">
			<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>>Francais</option>
			<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>>Italien</option>
			<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>>Anglais</option>
		</select>
		<input type="submit" data-role="button" data-inline="true" data-theme="b" value="<?= _("Update") ?>" />
	</form>
			
			</div>
			</div>
</div>

<? include("footer.php"); ?>
</div>