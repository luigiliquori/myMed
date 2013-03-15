<? include("header.php"); ?>
</head>
<body>

<div data-role="page" id="company" data-theme="b">	

	<? include("header-bar.php") ?>

	<div data-role="content" style="padding: 0px;">
		
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Please create your extended profile:") ?></h1>
		</div>
		
		<form data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false" data-theme='d'>
			<select name="profileFilled" id="profileFilled" data-theme="e" data-native-menu="false" onChange="
					
					switch ($('#profileFilled').val()) {
						
						case 'company':
    						$('#companyFields').show();	
    						$('#employeFields').hide();
  							break; 
  						
  						case 'employer':
    						$('#companyFields').hide();	
    						$('#employeFields').show();
  							break;					
					}">
					
					<option value="company" <?=(isset($_SESSION["profileFilled"]) && ($_SESSION["profileFilled"]=='company') || !isset($_SESSION["profileFilled"]))?"selected":""?>><?= _("Company")?></option>
					<option value="employer" <?=(isset($_SESSION["profileFilled"]) && $_SESSION["profileFilled"]=='employer')?"selected":""?>><?= _("Employer/Student")?></option>
			</select>
			<br>
				
			<div id="companyFields" <?=(isset($_SESSION["profileFilled"]) && ($_SESSION["profileFilled"]=='company') || !isset($_SESSION["profileFilled"]))?"":"style='display: none;'"?>>
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
			</div>

			<div id="employeFields" <?=(isset($_SESSION["profileFilled"]) && $_SESSION["profileFilled"]=='employer')?"":"style='display: none;'"?>>
				<label for="ecampus" ><?= _("Campus") ?> :</label>
				<input type="text" name="ecampus" id="ecampus" />
				<br/>
		
				<label for="euniv"><?= _("University") ?><b>*</b>  :</label>
				<input id="euniv" type="text" name="euniv" value="" />
				<br/>
				
				<label for="estudies"><?= _("Field of Studies") ?> :</label>
				<input id="estudies" type="text" name="estudies" value="" />
				<br/>
				
				<label for="enumber" ><?= _("Student number") ?><b>*</b>  :</label>
				<input type="text" name="enumber" />
			</div>
			
			<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			<center><input type="submit" value="<?= _("Submit") ?>" data-theme="g" data-icon="ok" data-inline="true" /></center>
		</form>
	</div>
<? include("footer.php"); ?>	
</div>
</body>
