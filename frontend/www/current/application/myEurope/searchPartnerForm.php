
<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
Template::init();


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="SearchPartner"  data-theme="d">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			<h1 style=" font-size: 200%; text-align: center;">Rechercher un partenaire:</h1>
			<br />
			<form action="search" id="searchForm">
				
				<input type="hidden" name="type" value="part" />
				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
						<legend>Type d'offre:</legend>
						<input type="checkbox" name="pacalabs" id="checkbox-01" /> <label for="checkbox-01">Pacalabs</label>
						<input type="checkbox" name="interreg" id="checkbox-02" /> <label for="checkbox-02">Interreg</label>
						<input type="checkbox" name="edu" id="checkbox-03" /> <label for="checkbox-03">Edu</label>
						<input type="checkbox" name="autre" id="checkbox-04" /> <label for="checkbox-04">Autre</label>

					</fieldset>
				</div>
				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
						<legend>Région/Etat concernés:</legend>
						<input type="checkbox" name="reg1" id="checkbox-1"/> <label for="checkbox-1">Région1</label>
						<input type="checkbox" name="reg2" id="checkbox-2"/> <label for="checkbox-2">Région2</label>
						<input type="checkbox" name="reg3" id="checkbox-3"/> <label for="checkbox-3">Région3</label>
						<input type="checkbox" name="reg4" id="checkbox-4"/> <label for="checkbox-4">Région4</label>
						<input type="checkbox" name="reg5" id="checkbox-5"/> <label for="checkbox-5">Région5</label>
						<input type="checkbox" name="reg6" id="checkbox-6"/> <label for="checkbox-6">Région6</label>
						<input type="checkbox" name="reg7" id="checkbox-7"/> <label for="checkbox-7">Région7</label>
						<input type="checkbox" name="reg8" id="checkbox-8"/> <label for="checkbox-8">Région8</label>
						<input type="checkbox" name="reg9" id="checkbox-9"/> <label for="checkbox-9">Région9</label>
					</fieldset>
				</div>
				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
						<legend>Métiers concernés:</legend>
						<input type="checkbox" name="met1" id="checkbox-11"/> <label for="checkbox-11">Métier1</label>
						<input type="checkbox" name="met2" id="checkbox-12"/> <label for="checkbox-12">Métier2</label>
						<input type="checkbox" name="met3" id="checkbox-13"/> <label for="checkbox-13">Métier3</label>
						<input type="checkbox" name="met4" id="checkbox-14"/> <label for="checkbox-14">Métier4</label>
						<input type="checkbox" name="met5" id="checkbox-15"/> <label for="checkbox-15">Métier5</label>
						<input type="checkbox" name="met6" id="checkbox-16"/> <label for="checkbox-16">Métier6</label>
						<input type="checkbox" name="met7" id="checkbox-17"/> <label for="checkbox-17">Métier7</label>
						<input type="checkbox" name="met8" id="checkbox-18"/> <label for="checkbox-18">Métier8</label>
						<input type="checkbox" name="met9" id="checkbox-19"/> <label for="checkbox-19">Métier9</label>
					</fieldset>
				</div>
				
				<div data-role="collapsible" data-mini="true" data-content-theme="d">
					<h3>Recherche avancée</h3>
					
					<div data-role="fieldcontain" id="dateselect">
					   <label for="select-choice-1">Entre les dates:</label>
					   <select name="dateMin" id="select-choice-1"  data-mini="true">
					    <option value="0">Date min</option>
					   <?php 
						for ($y=2012; $y<2017; $y++){
							for ($m=1; $m<13; $m++){
								?>
								 <option <?= ($y==2012 && $m==7)?'selected="selected"':'' ?>><?= $y.'-'.$m.'-1' ?></option>
								 <?php   		
							}
						}
					   ?>
					   </select>
					   <select name="dateMax" id="select-choice-1"  data-mini="true">
					    <option value="0">Date max</option>
					   <?php 
						for ($y=2012; $y<2017; $y++){
							for ($m=1; $m<13; $m++){
								?>
								 <option><?= $y.'-'.$m.'-1' ?></option>
								 <?php   		
							}
						}
					   ?>
					   </select>
					</div>	
					
					<div data-role="fieldcontain">
						<fieldset id="test2" data-role="controlgroup" data-type="horizontal" data-mini="true">
							<legend>Trier par Réputation:</legend>
							<select data-theme="b" data-mini="true" name="rate" data-role="slider">
								<option value="0">Non</option>
								<option value="1">Oui</option>
							</select>
						</fieldset>
					</div>
				</div>
				
				<div style="text-align: center;" >
					<input type="submit" data-theme="b" data-icon="search" data-inline="true" value="Chercher"/>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
