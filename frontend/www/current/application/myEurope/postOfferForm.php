
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
	<div data-role="page" id="PostOffer">
		<div class="wrapper">
			<div data-role="header" data-theme="c" style="max-height: 38px;">
				<h2>
					<a href="./" style="text-decoration: none;">myEurope</a>
				</h2>
			</div>
			<div data-role="content">
				<h1 style=" font-size: 200%; text-align: center;">Publier une offre:</h1>
				<br />
				<form action="post" method="post" id="publishForm">
					<input name="application" value="myEurope" type="hidden" />
					
					<input type="hidden" name="type" value="offer" />
					
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp2"> Libellé du projet: </label> <input id="textinputp2" data-mini="true" name="id" placeholder="" value="projet1" type="text" />
						</fieldset>
					</div>
					
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
							<input type="checkbox" name="met11" id="checkbox-11"/> <label for="checkbox-11">Métier1</label>
							<input type="checkbox" name="met12" id="checkbox-12"/> <label for="checkbox-12">Métier2</label>
							<input type="checkbox" name="met13" id="checkbox-13"/> <label for="checkbox-13">Métier3</label>
							<input type="checkbox" name="met14" id="checkbox-14"/> <label for="checkbox-14">Métier4</label>
							<input type="checkbox" name="met15" id="checkbox-15"/> <label for="checkbox-15">Métier5</label>
							<input type="checkbox" name="met16" id="checkbox-16"/> <label for="checkbox-16">Métier6</label>
							<input type="checkbox" name="met17" id="checkbox-17"/> <label for="checkbox-17">Métier7</label>
							<input type="checkbox" name="met18" id="checkbox-18"/> <label for="checkbox-18">Métier8</label>
							<input type="checkbox" name="met19" id="checkbox-19"/> <label for="checkbox-19">Métier9</label>
						</fieldset>
					</div>
					

						
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
							<label for="textinputi1">Date d'échéance: </label>
							<input id="textinputi1" name="date" placeholder="date" data-inline="true"
								value="2012-07-30" type="date" />
						</fieldset>
					</div>		

					<hr>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp7"> Contenu: </label>
							<textarea style="min-height: 200px;" id="textinputp7" name="text" placeholder="" ></textarea>
						</fieldset>
					</div>
					<input type="submit" data-theme="b" data-mini="true" value="Insérer" style="margin-right: auto; margin-left: auto;"/>
				</form>
				<div class="push"></div>
			</div>
		</div>
		<?= Template::credits(); ?>
	</div>
</body>
</html>
