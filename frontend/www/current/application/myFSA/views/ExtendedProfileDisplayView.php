<?include('../../system/lang/langue.php');
 include("header.php"); ?>
<div data-role="page" id="PublishView" data-theme="b">
<div class="wrapper">

<? include("header-bar.php"); ?>

<div data-role="content" data-theme="b">
	<!-- langue -->
	<form action="?action=extendedProfile" method="post" data-ajax="false">
			<label for="lang" ><?= translate("Language") ?>	: </label>
			<select id="lang" name="lang">
				<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>>Francais</option>
				<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>>Italien</option>
				<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>>Anglais</option>
			</select>
	<input type="submit" data-role="button" data-inline="true" data-theme="b" value="<?= translate("Update") ?>" data-icon="refresh"/>
	</form>
	
	<ul data-role="listview" class="ui-listview">
		<li class="ui-li ui-li-static ui-body-a ui-li-has-thumb">
			<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ui-li-thumb"/>
			<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
			<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
		</li>

		<?php if ($_SESSION["profileFilled"] == "company") {?>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading"> Full profile information</h3>
			<div>
						
				<!-- 	displaying array:
				
						$object = array( 
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]); -->
			
					<br> Company type : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
					
					<br> Company name :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
					
					<br> Company address : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
					
					<br> Company number :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
			</div>
		</li>		
		<?php }?>
		<?php if ($_SESSION["profileFilled"] == "employer") {?>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading"> Profil etendu</h3>
			<div>
						
				<!-- 	displaying array:
				
						$object = array( 
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]); -->
			
					<br> Campus : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
					
					<br> Universite :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
					
					<br> Filiere : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
					
					<br> Soumettre :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
			</div>
		</li>		
		<?php }?>
		
				<?php if ($_SESSION["profileFilled"] == "guest") {?>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading"> Full profile information</h3>
			<div>
						Vous etes connectes en tant qu'invite.
			</div>
		</li>		
		<?php }?>
	</ul>	
</div>
<? include("footer.php"); ?>