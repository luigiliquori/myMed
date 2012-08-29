<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>
<? 
 
 /** Definition of the Login / Register tabs */
 function tab_bar($activeTab) {
 	tabs(array(
 			"login" =>  APPLICATION_NAME,
 			"register" => _('Registration')), 
 		$activeTab);
 }
 
 ?>

<div data-role="page" id="login">
	
	<div data-role="content">
	
		<!-- Tabs -->
		<div data-role="header" data-theme="c" data-position="fixed">
			<? tab_bar("login") ?>
			<? include("notifications.php"); ?>
		</div>

		<!-- Login form -->
		<form data-role="content" action="index.php?action=login" data-ajax="false" method="post">
	
			<div style="margin-top: 10%;">
				<label for="login" ><?= _("Email") ?></label>
				<input type="text" id="login" data-inline="true" name="login" placeholder="<?= _("Email") ?>" />
				<label for="password" style="margin-top:5px;"><?= _("Password") ?></label>
				<input type="password" id="password" name="password" placeholder="<?= _("Password") ?>" />
				<div data-role="controlgroup" style="width:auto;text-align: center;" data-type="horizontal">
					<input type="submit" value="<?= _("Log In") ?>" data-inline="true" class="ui-btn-active ui-state-persist" />
				</div>
			</div>
			
			<div style="margin-top: 10%; text-align: center;" >
				<div data-role="collapsible" data-mini="true" data-inline="true">
					<h3 style="margin:auto;width:136px;"><?= _("About") ?></h3>
					<?= about(); ?>
				</div>
				<div class="myLogos">
					<img alt="Alcotra" src="../../system/img/logos/fullsize/alcotra" />
					<img alt="Europe" src="../../system/img/logos/fullsize/EU" style="opacity:.5;"/>
					<img alt="myMed" src="../../system/img/logos/mymed" />
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
		</div>

		<!--  Register form -->
		<form data-role="content" action="index.php?action=register" method="post">
		
			<label for="prenom"><?= _("first name") ?></label>
			<input id="prenom" type="text" name="prenom" value="" />
			
			<label for="nom"><?= _("last name") ?></label>
			<input id="nom" type="text" name="nom" value="" />
			
			<label for="email" ><?= _("Email") ?></label>
			<input type="text" id="email" name="email" value="" />
			
			<label for="password" ><?= _("Password") ?></label>
			<input type="password" id="password" name="password" />
			
			<label for="confirm" ><?= _("Password (confirmation)") ?></label>
			<input type="password" id="confirm" name="confirm" />
			<br/>
			
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 8px;"/>
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