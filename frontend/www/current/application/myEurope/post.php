<!DOCTYPE html>
<html>

<?php

	/*
	 *
	* usage:
	*  post
	*
	* what it does:
	*  display the form for posting new content: IMPORTANT this view should be reserved to special users
	*
	* 
	*/

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
?>

	<head>
		<?= $template->head(); ?>
	</head>
	<body>
		<div data-role="page" id="Post">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="search" data-icon="back"> Retour </a>
					<h3>myEurope - insertion</h3>
				</div>
				<div data-role="content">
					<?= isset($_GET['ok'])?"<div style='color:lightGreen;text-align:center;'>Contenu publié</div>":"" ?>
					<form action="controller" method="post" id="publishForm" data-ajax="false" >
						<input name="application" value="myEurope" type="hidden" />
						<input name="method" value='publish' type="hidden" />
						<input name="_desc" value="4" type="hidden" />
						<input name="~" value="" type="hidden" />
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp1"> Nom de l'organisme bénéficiaire: </label> <input id="textinputp1"  name="nom" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp2"> Libellé du projet: </label> <input id="textinputp2"  name="lib" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp3"> Coût total du projet (en euros): </label> <input id="textinputp3"  name="cout" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp4"> Montant du financement européen (en euros): </label> <input id="textinputp4"  name="montant" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp5"> Echéance: </label> <input id="textinputp5"  name="date" placeholder="" value="" type="date" />
							</fieldset>
						</div>
						<hr>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp6"> Description: </label> <textarea id="textinputp6"  name="desc" placeholder="" value=""></textarea>
							</fieldset>
						</div>
						<input type="submit" data-theme="g" value="Publier"/>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>