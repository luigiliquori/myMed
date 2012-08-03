<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>
<? 
 
 /** Definition of the Login / Register tabs */
 function tab_bar($activeTab) {
 	tabs(array(
 			"login" => "Login",
 			"register" => "Inscription"), 
 		$activeTab);
 }
 
 ?>

<div data-role="page" id="login">
	
	<div data-role="content">
	
		<!-- Tabs -->
		<div data-role="header" data-theme="c" data-position="fixed">
			<? tab_bar("login") ?>
			<? include("notifications.php")?>
		</div>

		<!-- Login form -->
		<form  data-role="content" action="index.php?action=login" method="post" data-ajax="false" >
	
			<input type="text" name="login" placeholder="<?= _("Email") ?>" />
			<input type="password" name="password" placeholder="<?= _("Password") ?>" />
			<div style="text-align: center;" >
				<input type="submit" value="<?= _("Log In") ?>" data-inline="true" data-theme="b" />
			</div>
			<div style="text-align: center;" >
				<br />
				<select data-theme="c" data-mini="true" name="slider" id="flip-d" data-role="slider"
					onchange="if ($(this).val()==1){$('#AboutContent').fadeOut('slow');} else {$('#AboutContent').fadeIn('slow')};">
					<option value="1"><?= _("About") ?></option>
					<option value="0"><?= _("About") ?></option>
				</select>
				<div id="AboutContent" style="display:none;">
					<?= about(); ?>
				</div>
			</div>
			
		</form>
		
	</div>
	
</div>
	
<div data-role="page" id="register" >	

	<div data-role="content">
	
		<!-- Tabs -->
		<div data-role="header" data-theme="c" data-position="fixed">
			<? tab_bar("register") ?>
			<? include("notifications.php")?>
		</div>

		<!--  Register form -->
		<form data-role="content" action="index.php?action=register" method="post" data-ajax="false" class="compact">
		
			<label for="prenom"><?= _("first name") ?></label>
			<input id="prenom" type="text" name="prenom" value="" />
			<br/>
			
			<label for="nom"><?= _("last name") ?></label>
			<input id="nom" type="text" name="nom" value="" />
			<br/>
			
			<label for="email" ><?= _("Email") ?></label>
			<input type="text" id="email" name="email" value="" />
			<br/>
			
			<label for="password" ><?= _("Password") ?></label>
			<input type="password" name="password" />
			<br/>
			
			<label for="password" ><?= _("Password (confirmation)") ?></label>
			<input type="password" name="confirm" id="confirm" />
			<br/>
			
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= MYMED_ROOT ?>/application/myMed/conds" rel="external">conditions d'utilisation</a>
			</span><br />
    		
			<br/>
			
			<div style="text-align: center;" >
				<input type="submit" value="<?= _("Validate") ?>" data-inline="true" data-theme="b" />
			</div>
		</form>
	</div>
	
</div>
<? include("footer.php"); ?>