<? include("header.php"); ?>

<? 
 
 /** Definition of the Login / Register tabs */
 function tab_bar($activeTab) {
 	tabs(array(
 			"company" => "Entreprise",
 			"employer" => "Employer/Etudiant", 
 			"guest" => "Invité"),
 		$activeTab);
 }
 
 ?>

<div data-role="page" id="company" data-theme="b">	

	<? include("header-bar.php") ?>

	<div data-role="content" style="padding: 0px;">
	
		<!-- Tabs -->
		<? tab_bar("company") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				<input type="hidden" name="profileFilled" id="profileFilled" value="company" />
				
				<label for="ctype" ><?= _("Type d'entreprise") ?></label>
				<input type="text" name="ctype" id="ctype" />
				<br/>
		
				<label for="cname"><?= _("Nom de l'entreprise") ?></label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= _("Adresse de l'entreprise") ?></label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="cnumber" ><?= _("SIRET") ?></label>
				<input type="text" name="cnumber" />
				<br/>
				
				<center><input type="submit" value="<?= _("Soumettre") ?>" data-theme="b" data-inline="true" /></center>
		
		</form>
	</div>
<? include("footer.php"); ?>	
</div>
	
<div data-role="page" id="employer" data-theme="b">	

	<? include("header-bar.php") ?>

	<div data-role="content" style="padding: 0px;">
	
		<!-- Tabs -->
		<? tab_bar("employer") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				<input type="hidden" name="profileFilled" id="profileFilled" value="employer" />
				
				<label for="occupation" ><?= _("Campus") ?></label>
				<input type="text" name="occupation" id="occupation" />
				<br/>
		
				<label for="cname"><?= _("Universite") ?></label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= _("Filiere") ?></label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="tnumber" ><?= _("Numero Etudiant") ?></label>
				<input type="text" name="tnumber" />
				<br/>
				
				<center><input type="submit" value="<?= _("Soumettre") ?>" data-theme="b" data-inline="true" /></center>
		
		</form>
	</div>
<? include("footer.php"); ?>	
</div>

<div data-role="page" id="guest" data-theme="b">

	<? include("header-bar.php") ?>
	
	<div data-role="content" style="padding: 0px;">
	
		<!-- Tabs -->
		<? tab_bar("guest") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				
				<input type="hidden" name="profileFilled" id="profileFilled" value="guest" />		
				
				<fieldset data-role="controlgroup">
	    			Après une inscription comme invité vous pourrez seulement utiliser quelques options de myFSA
	    			<input id="service-term" type="checkbox" name="checkCondition" />
					<label for="service-term"><?= _("Accepter") ?></label>
				</fieldset>	 		
				<br/>
				
				<center><input type="submit" value="Soumettre" data-theme="b" data-inline="true" /></center>
		
		</form>
		
	</div>
<? include("footer.php"); ?>	
</div>
