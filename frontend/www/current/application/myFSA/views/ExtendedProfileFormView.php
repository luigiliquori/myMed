<? 

include("header.php"); ?>

<? 
 
 /** Definition of the Login / Register tabs */
 function tab_bar($activeTab) {
 	tabs(array(
 			"company" => "Company",
 			"employer" => "Employer/Student", 
 			"guest" => "Guest"),
 		$activeTab);
 }
 
 ?>

<div data-role="page" id="company" data-theme="a">	

	<? include("header-bar.php") ?>

	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("company") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				<input type="hidden" name="profileFilled" id="profileFilled" value="company" />
				
				<label for="ctype" ><?= _("Company type") ?></label>
				<input type="text" name="ctype" id="ctype" />
				<br/>
		
				<label for="cname"><?= _("Company name") ?></label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= _("Company address") ?></label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="cnumber" ><?= _("Security number") ?></label>
				<input type="text" name="cnumber" />
				<br/>
				
				<input type="submit" value="<?= _("Submit") ?>" data-theme="a" />
		
		</form>
	</div>
	
</div>
	
<div data-role="page" id="employer" data-theme="a">	

	<? include("header-bar.php") ?>

	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("employer") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				<input type="hidden" name="profileFilled" id="profileFilled" value="employer" />
				
				<label for="occupation" ><?= _("Occupation") ?></label>
				<input type="text" name="occupation" id="occupation" />
				<br/>
		
				<label for="cname"><?= _("Company name") ?></label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= _("Company address") ?></label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="tnumber" ><?= _("Telephone number") ?></label>
				<input type="text" name="tnumber" />
				<br/>
				
				<input type="submit" value="<?= _("Submit") ?>" data-theme="a" />
		
		</form>
	</div>
	
</div>

<div data-role="page" id="guest" data-theme="a">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("guest") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				
				<input type="hidden" name="profileFilled" id="profileFilled" value="guest" />		
				
				<fieldset data-role="controlgroup">
	    			After registering as a guest You can use only some option in myFSA
	    			<input id="service-term" type="checkbox" name="checkCondition" />
					<label for="service-term"><?= _("Accept") ?></label>
				</fieldset>	 		
				<br/>
				
				<input type="submit" value="<?= _("Register") ?>" data-theme="a" />
		
		</form>
		
	</div>
	
</div>
<? include("footer.php"); ?>