<? 

//
// This view shows both the login and register forms, with two tabs
//

include("header.php"); ?>
<div>
	<!--  Tabs -->
	<div data-role="navbar">
    <ul>
        <li><a href="#login"    data-theme="b" <?= ($TAB != "register") ? 'data-click-on-load' : '' ?> ><?= _("Login") ?></a></li>
        <li><a href="#register" data-theme="b" <?= ($TAB == "register") ? 'data-click-on-load' : '' ?> ><?= _("Register") ?></a></li>
    </ul>

	<!-- Login form -->
	<form id="login" action="index.php?action=login" method="post" data-ajax="false" class="tab" >
	
		<input type="text" name="login" placeholder="Login" />
		<input type="password" name="password" placeholder="Password" />
	
		<input type="submit" value="<?= _("Connection") ?>" data-theme="b" />
	
	</form>
	
	<!--  Register form -->
	<form  id="register" action="index.php?action=register" method="post" data-ajax="false" class="tab" >
	
			<label for="prenom"><?= _("Prénom") ?></label>
			<input id="prenom" type="text" name="prenom" value="" />
			
			<label for="nom"><?= _("Nom") ?></label>
			<input id="nom" type="text" name="nom" value="" />
			
			<label for="email" ><?= _("eMail") ?></label>
			<input type="text" id="email" name="email" value="" />
			
			<label for="password" ><?= _("Mot de passe") ?></label>
			<input type="password" name="password" />
			
			<label for="password" ><?= _("Mot de passe (confirmation)") ?></label>
			<input type="password" name="confirm" id="confirm" />
						
			<fieldset data-role="controlgroup">
    			Lire les <a href="http://" rel="external" data-role="none"><?= _("Conditions d'utilisation") ?></a>
    			<input id="service-term" type="checkbox" name="checkCondition" />
				<label for="service-term"><?= _("J'accepte") ?></label>
			</fieldset>	 		
			
			<input type="submit" value="<?= _("Register") ?>" data-theme="b" />
	
	</form>
	
</div>
<? include("footer.php"); ?>