<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<? 


/**
 *  Generates a navbar with appropriate transitions (left / right).
 *  $tabs : An array of "tabId" => "<Label>"
 *  $ActiveTab : Current active tab 
 */
function tabs($tabs, $activeTab) {
	
	$reverse = true;
	?> 	
	<div data-role="navbar"> 
	<ul>
	<? foreach ($tabs as $id => $label) { ?>
		<li>
			<a 
				href="#<?= $id ?>"    
				data-transition="slide" 
				data-theme="b" 
				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
				<?= ($activeTab == $id) ? 'class="ui-btn-active ui-state-persist"' : '' ?> >
				<?= $label ?>
			</a>
		</li><? 
		
		if ($id == $activeTab) {
			$reverse = false;
		}
	}

	?> 
	</ul>
	</div> <?
 } 
 
 /** Login / Register tabs */
 function tab_header($activeTab) {
 	?><div data-role="header"><?
 	tabs(array(
 			"login" => "Login",
 			"register" => "Register"), 
 		$activeTab);
 	?></div><? 
 }
 
 ?>

<div data-role="page" id="login">

	<!-- Tabs -->
	<? tab_header("login") ?>

	<!-- Login form -->
	<form  data-role="content" action="index.php?action=login" method="post" data-ajax="false" >
	
		<input type="text" name="login" placeholder="Login" />
		<input type="password" name="password" placeholder="Password" />
	
		<input type="submit" value="<?= _("Connection") ?>" data-theme="b" />
	
	</form>
	
</div>
	
<div data-role="page" id="register" >	

	<!-- Tabs -->
	<? tab_header("register") ?>

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
<? include("footer.php"); ?>