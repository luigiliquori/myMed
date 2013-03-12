<? include("header.php"); ?>
</head>
<body>
<? 
 
 /** Definition of the Login / Register tabs */
 function tab_bar($activeTab) {
 	tabs(array(
 			"company" => _('Company'),
 			"employer" => _('Employer/Student')),
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
				
				<label for="ctype" ><?= _("Company type") ?><b>*</b> :</label>
				<input type="text" name="ctype" id="ctype" />
				<br/>
		
				<label for="cname"><?= _("Company name") ?><b>*</b>  :</label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= _("Company Address") ?><b>*</b>  :</label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="cnumber" ><?= _("SIRET") ?><b>*</b>  :</label>
				<input type="text" name="cnumber" />
				<br>
				<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
				
				<center><input type="submit" value="<?= _("Submit") ?>" data-theme="b" data-inline="true" /></center>
		
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
				
				<label for="occupation" ><?= _("Campus") ?> :</label>
				<input type="text" name="occupation" id="occupation" />
				<br/>
		
				<label for="cname"><?= _("University") ?><b>*</b>  :</label>
				<input id="cname" type="text" name="cname" value="" />
				<br/>
				
				<label for="caddress"><?= _("Field of Studies") ?> :</label>
				<input id="caddress" type="text" name="caddress" value="" />
				<br/>
				
				<label for="tnumber" ><?= _("Student number") ?><b>*</b>  :</label>
				<input type="text" name="tnumber" />
				<br>
				<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
				
				<center><input type="submit" value="<?= _("Submit") ?>" data-theme="b" data-inline="true" /></center>
		
		</form>
	</div>
<? include("footer.php"); ?>	
</div>
