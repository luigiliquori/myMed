<?php

/*
*  update your profile
*/

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();

$msg="";

if (isset($_POST['role'])) {
	//extended Profile (user's role)
	
	$permission = (
			strpos($_SESSION['user']->email, "@inria.fr") !== false ||
			$_SESSION['user']->email=="other@mail.com" )
			? 2 : 2;

	$data = array();	
	array_push($data, new DataBean("all", KEYWORD, array("")));
	array_push($data, new DataBean("id", TEXT, array($_SESSION['user']->id)));
	array_push($data, new DataBean("role", TEXT, array($_POST['role'])));
	array_push($data, new DataBean("permission", TEXT, array($permission)));
	
	$request = new Request("v2/PublishRequestHandler", CREATE);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("namespace", "users");

	$request->addArgument("data", json_encode($data));
	$request->addArgument("user", $_SESSION['user']->id);
	$request->addArgument("id", $_SESSION['user']->id);
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status != 200) {
		$msg = $responseObject->description;
	} else {
		$_SESSION['profile'] = new stdClass(); //@TODO create profile class
		$_SESSION['profile']->role = $_POST['role'];
		$_SESSION['profile']->permission = $permission;
		//header("Location: ./");
	}
}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>


<body>
<?php 
var_dump($responseObject);

?>
	<div data-role="page" id="Association" data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a href="./option" data-transition="flip" data-direction="reverse" data-icon="back">Retour</a></li>
					<li><a href="option" data-icon="check" data-theme="b" data-mini="true" onclick="$('#updateExtendedForm').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2>Profile myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm" data-ajax="false">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="./updateExtended#Association" class="ui-btn-active ui-state-persist">Association</a></li>
						<li><a href="./updateExtended#Entreprise">Entreprise</a></li>
						<li><a href="./updateExtended#EtabPublic">Etablissement public</a></li>
						<li><a href="./updateExtended#Mairie ">Mairie </a></li>
						<li><a href="./updateExtended#Région">Région</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Association" />

				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> Nom de l'association: </label> <input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['extendedProfile']->name ?>'
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu2"> Domaine d'action: </label> <input id="textinputu2" name="activity" placeholder="" value=''
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu3"> N°SIRET: </label> <input id="textinputu3" name="siret" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu4"> Adresse: </label> <input id="textinputu4" name="address" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu5"> Téléphone: </label> <input id="textinputu5" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
	<div data-role="page" id="Entreprise">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-transition="flip" data-direction="reverse" data-icon="back">Retour</a></li>
					<li><a href="option" data-icon="check" data-theme="b" data-mini="true" onclick="$('#updateExtendedForm').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm"  data-ajax="false">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="./updateExtended#Association">Association</a></li>
						<li><a href="./updateExtended#Entreprise" class="ui-btn-active ui-state-persist">Entreprise</a></li>
						<li><a href="./updateExtended#EtabPublic">Etablissement public</a></li>
						<li><a href="./updateExtended#Mairie ">Mairie </a></li>
						<li><a href="./updateExtended#Région">Région</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Entreprise" />

				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> Nom de l'entreprise: </label> <input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['extendedProfile']->name ?>'
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu2"> Activité commerciale: </label> <input id="textinputu2" name="activity" placeholder="" value=''
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu3"> N°SIRET: </label> <input id="textinputu3" name="siret" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu4"> Adresse: </label> <input id="textinputu4" name="address" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu5"> Téléphone: </label> <input id="textinputu5" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
	<div data-role="page" id="EtabPublic">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-transition="flip" data-direction="reverse" data-icon="back">Retour</a></li>
					<li><a href="option" data-icon="check" data-theme="b" data-mini="true" onclick="$('#updateExtendedForm').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2>Profile myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm"  data-ajax="false">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="./updateExtended#Association">Association</a></li>
						<li><a href="./updateExtended#Entreprise">Entreprise</a></li>
						<li><a href="./updateExtended#EtabPublic" class="ui-btn-active ui-state-persist">Etablissement public</a></li>
						<li><a href="./updateExtended#Mairie ">Mairie </a></li>
						<li><a href="./updateExtended#Région">Région</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="EtabPublic" />

				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> Type d’établissement: </label> <input id="textinputu1" name="subtype" placeholder="" value='<?= $_SESSION['extendedProfile']->name ?>'
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu2"> Ville/Commune: </label> <input id="textinputu2" name="activity" placeholder="" value=''
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu3"> Nom de l’établissement: </label> <input id="textinputu3" name="name" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu4"> Adresse: </label> <input id="textinputu4" name="address" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu5"> Téléphone: </label> <input id="textinputu5" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
		<div data-role="page" id="Mairie">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-transition="flip" data-direction="reverse" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" data-mini="true" onclick="$('#updateExtendedForm').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2>Profile myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm" data-ajax="false">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="./updateExtended#Association">Association</a></li>
						<li><a href="./updateExtended#Entreprise">Entreprise</a></li>
						<li><a href="./updateExtended#EtabPublic">Etablissement public</a></li>
						<li><a href="./updateExtended#Mairie"  class="ui-btn-active ui-state-persist">Mairie </a></li>
						<li><a href="./updateExtended#Région">Région</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Mairie" />

			
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu2"> Ville/Commune: </label> <input id="textinputu2" name="activity" placeholder="" value=''
							type="text" />
					</fieldset>
				</div>

				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu4"> Adresse: </label> <input id="textinputu4" name="address" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu5"> Téléphone: </label> <input id="textinputu5" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
		<div data-role="page" id="Région">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-transition="flip" data-direction="reverse" data-icon="back">Retour</a></li>
					<li><a href="option" data-icon="check" data-theme="b" data-mini="true" onclick="$('#updateExtendedForm').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2>Profile myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm"  data-ajax="false">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="./updateExtended#Association">Association</a></li>
						<li><a href="./updateExtended#Entreprise">Entreprise</a></li>
						<li><a href="./updateExtended#EtabPublic">Etablissement public</a></li>
						<li><a href="./updateExtended#Mairie ">Mairie </a></li>
						<li><a href="./updateExtended#Région" class="ui-btn-active ui-state-persist">Région</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Région" />

				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
						<legend>
							Région à renseigner:
						</legend>
						<input type="radio" name="name" id="radio-view-a" value="PACA" /> <label for="radio-view-a">PACA</label>
						<input type="radio" name="name" id="radio-view-b" value="Piémont" /> <label for="radio-view-b">Piémont</label>
						<input type="radio" name="name"id="radio-view-c" value="Ligurie" /> <label for="radio-view-c">Ligurie</label>
						<input type="radio" name="name" id="radio-view-d" value="Vallée d'Aoste" /><label for="radio-view-d">Vallée d'Aoste</label>
						<input type="radio" name="name" id="radio-view-d" value="Vallée d'Aoste" /><label for="radio-view-d">Rhône-Alpes</label>
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu4"> Adresse: </label> <input id="textinputu4" name="address" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu5"> Téléphone: </label> <input id="textinputu5" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
