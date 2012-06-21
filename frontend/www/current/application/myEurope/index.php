<?php

	//ob_start("ob_gzhandler");
	
	require_once 'Template.class.php';
	$template = new Template();
	$template->checkSession();

	if (isset($_GET['registration']) || (isset($_GET['userID']))) {
		header("Location: ./option?".$_SERVER['QUERY_STRING']);
	} else if (!isset($_SESSION['user'])) {
		header("Location: ./authenticate");
	}
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>

	<body>
		<div data-role="page" id="Home">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<h2></h2>
					<a href="about" data-theme="b" type="button" data-icon="info" data-transition="slide" data-direction="reverse" >about</a>
					<a href="post" type="button" class="ui-btn-right" data-theme="d" style="position: absolute;left: 40%;width:20%;">Insérer</a>
					<a id="opt" href=<?= $_SESSION['user']?"option":"authenticate" ?> class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">	
					<div style="margin-top:2em; margin-bottom:auto;text-align: center;">
					<h1 style="color: #0060AA;font-size:350%;">myEurope</h1>
					<form action="search" id="subscribeForm">
						<input name="q" placeholder="chercher un partenaire ou une offre par mot clés" value="" data-type="search"/>
						<input name="type" value="partenaires" type="hidden" />
					</form>
					<br /><br />
					<span style="margin:5px 15px;">ou par ses catégories:</span>
					<div data-role="fieldcontain">
					    <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
					    	<legend>Type d'offre:</legend>
					    	<input type="checkbox" name="offre" id="checkbox-5" class="custom" />
							<label for="checkbox-5">Pacalabs</label>
							
					    	<input type="checkbox" name="offre" id="checkbox-6" class="custom" />
							<label for="checkbox-6">Interreg</label>
			
							<input type="checkbox" name="offre" id="checkbox-7" class="custom" />
							<label for="checkbox-7">National</label>
			
							<input type="checkbox" name="offre" id="checkbox-8" class="custom" />
							<label for="checkbox-8">Autre</label>    
					    </fieldset>
					</div>
					<div data-role="fieldcontain">
					    <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
					    	<legend>Métier:</legend>
					    	<input type="checkbox" name="offre" id="checkbox-9" class="custom" />
							<label for="checkbox-9">Métier1</label>
							
					    	<input type="checkbox" name="offre" id="checkbox-10" class="custom" />
							<label for="checkbox-10">Métier2</label>
			
							<input type="checkbox" name="offre" id="checkbox-11" class="custom" />
							<label for="checkbox-11">Métier3</label>  
					    </fieldset>
					</div>
					<span style="margin:5px 15px;">entre date1 et date2 : TODO</span>
					</div>
					<br />
					<a href="search" type="button" data-mini="true" data-icon="search"
							style="width: 200px; margin-right: auto; margin-left: auto;">Chercher</a>
					
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
