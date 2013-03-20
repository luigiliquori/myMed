<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<? include("header.php"); ?>
</head>
<body>

<div data-role="page" id="company" data-theme="b">	

	<div data-role="header" data-theme="b" data-position="fixed">
		<h1 style="color: white;"><?= _("Extended profile") ?></h1>
		<a href="?action=main" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
	</div>

	<div data-role="content" style="padding: 0px;">
		
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Please create your extended profile:") ?></h1>
		</div>
		
		<form data-role="content" action="index.php?action=ExtendedProfile" method="post" data-ajax="false" data-theme='d'>
			<select name="profileFilled" id="profileFilled" data-theme="d" data-native-menu="false" onChange="
					
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
</div>
</body>
