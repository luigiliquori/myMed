<? include("header.php"); ?>

<? 
 
 /** Definition of the Login / Register tabs */
 function tab_bar($activeTab) {
 	tabs(array(
 			"company" => translate('Company'),
 			"employer" => translate('Employer/Student'), 
 			"guest" => translate('Guest')),
 		$activeTab);
 }
 
 ?>

<div data-role="page" id="company" data-theme="b">	

	<? include("header-bar.php") ?>

	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("company") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				<input type="hidden" name="profileFilled" id="profileFilled" value="company" />
				
				<label for="ctype" ><?= translate("Company type") ?></label>
				<input type="text" name="ctype" id="ctype" />
				<br/>
		
				<label for="cname"><?= translate("Company name") ?></label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= translate("Company Address") ?></label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="cnumber" ><?= translate("SIRET") ?></label>
				<input type="text" name="cnumber" />
				<br/>
				
				<input type="submit" value="<?= translate("Submit") ?>" data-theme="b" />
		
		</form>
	</div>
<? include("footer.php"); ?>	
</div>
	
<div data-role="page" id="employer" data-theme="b">	

	<? include("header-bar.php") ?>

	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("employer") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				<input type="hidden" name="profileFilled" id="profileFilled" value="employer" />
				
				<label for="occupation" ><?= translate("Campus") ?></label>
				<input type="text" name="occupation" id="occupation" />
				<br/>
		
				<label for="cname"><?= translate("University") ?></label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= translate("Field of Studies") ?></label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="tnumber" ><?= translate("Student number") ?></label>
				<input type="text" name="tnumber" />
				<br/>
				
				<input type="submit" value="<?= translate("Submit") ?>" data-theme="b" />
		
		</form>
	</div>
<? include("footer.php"); ?>	
</div>

<div data-role="page" id="guest" data-theme="b">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<!-- Tabs -->
		<? tab_bar("guest") ?>

		<form  data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false">
				
				<input type="hidden" name="profileFilled" id="profileFilled" value="guest" />		
				
				<fieldset data-role="controlgroup">
	    			<?= translate('After sign in as a guest you cannot use all options in myFSA')?>
	    			<input id="service-term" type="checkbox" name="checkCondition" />
					<label for="service-term"><?= _("Accept") ?></label>
				</fieldset>	 		
				<br/>
				
				<input type="submit" value="<?= _("Submit") ?>" data-theme="b" />
		
		</form>
		
	</div>
<? include("footer.php"); ?>	
</div>
