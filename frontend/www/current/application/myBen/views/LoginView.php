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
 			"register" => "Register"), 
 		$activeTab);
 }
 
 ?>

<div data-role="page" id="login">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("login") ?>

		<!-- Login form -->
		<form  data-role="content" action="index.php?action=login" method="post" data-ajax="false" >
	
			<input type="text" name="login" placeholder="Login" />
			<input type="password" name="password" placeholder="Password" />
	
			<input type="submit" value="<?= _("Connection") ?>" data-theme="b" />
	
		</form>
		
	</div>
	
</div>
	
<div data-role="page" id="register" >	

	<? include("header-bar.php") ?>

	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("register") ?>

		<!--  Register form -->
		<form  data-role="content" action="index.php?action=register" method="post" data-ajax="false">
		
				<label for="prenom"><?= _("Prénom") ?></label>
				<input id="prenom" type="text" name="prenom" value="" />
				<br/>
				
				<label for="nom"><?= _("Nom") ?></label>
				<input id="nom" type="text" name="nom" value="" />
				<br/>
				
				<label for="email" ><?= _("eMail") ?></label>
				<input type="text" id="email" name="email" value="" />
				<br/>
				
				<label for="password" ><?= _("Mot de passe") ?></label>
				<input type="password" name="password" />
				<br/>
				
				<label for="password" ><?= _("Mot de passe (confirmation)") ?></label>
				<input type="password" name="confirm" id="confirm" />
				<br/>
							
				<fieldset data-role="controlgroup">
	    			Lire les <a href="http://" rel="external" ><?= _("Conditions d'utilisation") ?></a>
	    			<input id="service-term" type="checkbox" name="checkCondition" />
					<label for="service-term"><?= _("J'accepte") ?></label>
				</fieldset>	 		
				<br/>
				
				<input type="submit" value="<?= _("Register") ?>" data-theme="b" />
		
		</form>
	</div>
	
</div>
<? include("footer.php"); ?>