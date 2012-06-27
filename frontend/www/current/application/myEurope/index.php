<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
$template = new Template();
$template->init();

if (isset($_GET['registration']) || (isset($_GET['userID']))) {
	header("Location: ./option?".$_SERVER['QUERY_STRING']);
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
				<a href="about" data-theme="b" type="button" data-icon="info" data-transition="slide" data-direction="reverse">about</a> 
				
				<a href="<?= $_SESSION['userPerm']>0?"post":"" ?>"
					type="button" class="ui-btn-right" data-theme="d" style="position: absolute; left: 40%; width: 20%;">Insérer (restreint à *@inria.fr)</a> <a id="opt"
					href=<?= $_SESSION['user']?"option":"authenticate" ?> class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?>
				</a>
			</div>
			<div data-role="content">
				<div style="margin-top: 2em; margin-bottom: auto;">
					<h1 style="color: #0060AA; font-size: 350%; text-align: center;">myEurope</h1>
					<form action="search" id="searchForm">
						<input name="q" placeholder="chercher un partenaire ou une offre par mot clés" value="" data-type="search" id="tagSearch"/> <input name="type"
							value="partenaires" type="hidden" /> <br /> <br /> <span style="margin: 5px 15px;">ou par ses catégories:</span><br /><br />
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
								<legend>Type d'offre:</legend>
								<input type="radio" name="type" id="checkbox-5" /> <label for="checkbox-5">Pacalabs</label> <input
									type="radio" name="type" id="checkbox-6" /> <label for="checkbox-6">Interreg</label> <input
									type="radio" name="type" id="checkbox-7" /> <label for="checkbox-7">Edu</label> <input type="radio" name="type" id="checkbox-8" /> <label
									for="checkbox-8">Autre</label>
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
								<legend>Métier:</legend>
								<input type="radio" name="metier" id="checkbox-9" class="custom" /> <label for="checkbox-9">Métier1</label> <input type="radio"
									name="metier" id="checkbox-10" class="custom" /> <label for="checkbox-10">Métier2</label> <input type="radio" name="metier"
									id="checkbox-11" class="custom" /> <label for="checkbox-11">Métier3</label>
							</fieldset>
						</div>

						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
								<label for="textinputi1"> Date d'échéance (min/max): </label> <input id="textinputi1" name="dateMin" placeholder="Date min" data-inline="true"
									value="2012-06-30" type="date" style="width: 150px;" /> <input name="dateMax" placeholder="Date max" data-inline="true" value="2012-07-30"
									type="date" style="width: 150px;" />
							</fieldset>
						</div>
						
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup" data-type="horizontal" class="dualSlider">
								
								<label for="buying_slider_min">Réputation (min/max)</label>
								<input type="range" name="rateMax" id="buying_slider_max" placeholder="Rate max" value="5" min="0" max="5" data-mini="true" />
								<input type="range" name="rateMin" id="buying_slider_min" placeholder="Rate min" value="" min="0" max="5" data-highlight="true" data-mini="true" />

							</fieldset>
						</div>

					</form>

				</div>
				<br /> <a href="" type="button" data-theme="g" data-mini="true" data-icon="search" onclick="$('#searchForm').submit();"
					style="width: 200px; margin-right: auto; margin-left: auto;">Chercher</a>
				<div class="push"></div>
			</div>
		</div>
		<?= $template->credits(); ?>
	</div>
</body>
</html>
