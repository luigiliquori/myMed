
<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
Template::init();
Template::checksession();


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="SearchPartner"  data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>
					<li><a data-icon="search" data-theme="b" onclick="$('#searchForm').submit();">Chercher</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h3 style="text-align:center;">
				<a href="" style="text-decoration: none;">Rechercher un partenariat:</a>
			</h3>
			<form action="search" id="searchForm">
				
				<input type="hidden" name="namespace" value="part" />
				
				<div data-role="fieldcontain" id="themecheckboxes">
				 	<fieldset data-role="controlgroup">
						<legend>Thèmes:</legend>
						
						<input type="checkbox" id="checkbox-all" />
						<label for="checkbox-all">Tous</label>
						
						<input type="checkbox" name="theme1" id="checkbox-1a"/>
						<label for="checkbox-1a">Education, culture & sport</label>
	
						<input type="checkbox" name="theme2" id="checkbox-2a"/>
						<label for="checkbox-2a">Emploi, affaires sociales & égalité des chances</label>
						
						<input type="checkbox" name="theme3" id="checkbox-3a"/>
						<label for="checkbox-3a">Entreprises & innovation</label>
	
						<input type="checkbox" name="theme4" id="checkbox-4a"/>
						<label for="checkbox-4a">Environnement, énergie & transports</label>
						
						<input type="checkbox" name="theme5" id="checkbox-5a" />
						<label for="checkbox-5a">Agriculture</label>
	
						<input type="checkbox" name="theme6" id="checkbox-6a" />
						<label for="checkbox-6a">Pêche</label>
						
						<input type="checkbox" name="theme7" id="checkbox-7a"/>
						<label for="checkbox-7a">Cohésion économique et sociale</label>
	
						<input type="checkbox" name="theme8" id="checkbox-8a" />
						<label for="checkbox-8a">Recherche</label>
						
						<input type="checkbox" name="theme9" id="checkbox-9a" />
						<label for="checkbox-9a">Santé & protection des consommateurs</label>
						
				    </fieldset>
				</div>
				
				<div data-role="fieldcontain">
				 	<fieldset data-role="controlgroup">
						<legend>Pays:</legend>
						<input type="checkbox" name="reg1" id="checkbox-1a" checked="checked"/>
						<label for="checkbox-1a">France</label>
	
						<input type="checkbox" name="reg2" id="checkbox-2a"/>
						<label for="checkbox-2a">Italie</label>
						
				    </fieldset>
				</div>

				<div data-role="fieldcontain">
					<label for="search">ou Mots clés:</label>
					<input type="search" name="q" id="search" value="projet test" />
				</div>
				
				
<!--				<div style="text-align: center;" > -->
<!-- 					<input type="submit" data-theme="b" data-icon="search" data-inline="true" value="Chercher"/> -->
<!-- 				</div> -->
			</form>
		</div>
	</div>
</body>
</html>
