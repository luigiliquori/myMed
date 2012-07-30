<?php

/*
*  update your profile
*/

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();
Template::checksession();

$msg="";
$i=0;

if (isset($_POST['role'])) {
	//extended Profile (user's role)
	
	if ( empty($_POST["email"]) && empty($_POST["phone"])){
		$msg = "<span style='color: red; text-align:center;'>Veuillez renseigner un mode de contact</span>";
	}
	if ( empty($_POST["checkCondition"])){
		$msg = "<span style='color: red;text-align:center; '>Veuillez accepter les conditions d'utilisation</span>";
	}
	
	if ($msg == ""){
		$permission = (
				strpos($_SESSION['user']->email, "@inria.fr") !== false ||
				$_SESSION['user']->email=="other@mail.com" )
				? 2 : 0;
		
		$data = array();
		array_push($data, new DataBean("role", DATA, $_POST['role']));
		array_push($data, new DataBean("permission", DATA, $permission));
		array_push($data, new DataBean("name", DATA, $_POST['name']));
		array_push($data, new DataBean("activity", TEXT, $_POST['activity']));
		array_push($data, new DataBean("address", TEXT, $_POST['address']));
		array_push($data, new DataBean("email", DATA, $_POST['email']));
		array_push($data, new DataBean("phone", TEXT, $_POST['phone']));
		array_push($data, new DataBean("siret", TEXT, $_POST['siret']));
		
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
			$msg = $responseObject->description;
			$_SESSION['profile'] = new stdClass(); //@TODO create profile class
			$_SESSION['profile']->role = $_POST['role'];
			$_SESSION['profile']->permission = $permission;
			header("Location: ./");
		}
	}
	
}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>
<body>
<div id="testest">
</div>
	
	<div data-role="page" id="Association" data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-mini="true"  data-iconpos="left">
				<ul>
					<li><a href="option" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" onclick="$('#updateExtendedForm<?= ++$i ?>').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm<?= $i ?>">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar" data-mini="true">
					<ul>
						<li><a href="#Association" class="ui-btn-active ui-state-persist">Association</a></li>
						<li><a href="#Entreprise">Entreprise</a></li>
						<li><a href="#EtabPublic">Etablissement public</a></li>
						<li><a href="#Mairie">Mairie </a></li>
						<li><a href="#Région">Région</a></li>
						<li><a href="#Département">Département</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Association" />

				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> Nom de l'association: </label> <input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['user']->name ?>'
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
						<label for="textinputu5"> Email: </label> <input id="textinputu5" name="email" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu6"> Téléphone: </label> <input id="textinputu6" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
				</div>
			</form>
		</div>
	</div>
	<div data-role="page" id="Entreprise">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-mini="true"  data-iconpos="left">
				<ul>
					<li><a href="option" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" onclick="$('#updateExtendedForm<?= ++$i ?>').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm<?= $i ?>" >

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="#Association">Association</a></li>
						<li><a href="#Entreprise" class="ui-btn-active ui-state-persist">Entreprise</a></li>
						<li><a href="#EtabPublic">Etablissement public</a></li>
						<li><a href="#Mairie">Mairie </a></li>
						<li><a href="#Région">Région</a></li>
						<li><a href="#Département">Département</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Entreprise" />

				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> Nom de l'entreprise: </label> <input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['user']->name ?>'
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
						<label for="textinputu5"> Email: </label> <input id="textinputu5" name="email" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu6"> Téléphone: </label> <input id="textinputu6" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
				</div>
			</form>
		</div>
	</div>
	<div data-role="page" id="EtabPublic">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-mini="true"  data-iconpos="left">
				<ul>
					<li><a href="option" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" onclick="$('#updateExtendedForm<?= ++$i ?>').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm<?= $i ?>">

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="#Association">Association</a></li>
						<li><a href="#Entreprise">Entreprise</a></li>
						<li><a href="#EtabPublic" class="ui-btn-active ui-state-persist">Etablissement public</a></li>
						<li><a href="#Mairie">Mairie </a></li>
						<li><a href="#Région">Région</a></li>
						<li><a href="#Département">Département</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="EtabPublic" />

				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu3"> Nom de l’établissement: </label> <input id="textinputu3" name="name" placeholder="" value='<?= $_SESSION['user']->name ?>'
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> Type d’établissement: </label> <input id="textinputu1" name="activity" placeholder="" value=''
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
						<label for="textinputu5"> Email: </label> <input id="textinputu5" name="email" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu6"> Téléphone: </label> <input id="textinputu6" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
				</div>
			</form>
		</div>
	</div>
		<div data-role="page" id="Mairie">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-mini="true"  data-iconpos="left">
				<ul>
					<li><a href="option" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" onclick="$('#updateExtendedForm<?= ++$i ?>').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm<?= $i ?>">
				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="#Association">Association</a></li>
						<li><a href="#Entreprise">Entreprise</a></li>
						<li><a href="#EtabPublic">Etablissement public</a></li>
						<li><a href="#Mairie" class="ui-btn-active ui-state-persist">Mairie </a></li>
						<li><a href="#Région">Région</a></li>
						<li><a href="#Département">Département</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Mairie" />

			
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu3"> Ville/Commune: </label> <input id="textinputu3" name="name" placeholder="" value='<?= $_SESSION['user']->name ?>'
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
						<label for="textinputu5"> Email: </label> <input id="textinputu5" name="email" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu6"> Téléphone: </label> <input id="textinputu6" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
				</div>
			</form>
		</div>
	</div>
	
	
	<div data-role="page" id="Région">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-mini="true"  data-iconpos="left">
				<ul>
					<li><a href="option" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" onclick="$('#updateExtendedForm<?= ++$i ?>').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm<?= $i ?>" >

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="#Association">Association</a></li>
						<li><a href="#Entreprise">Entreprise</a></li>
						<li><a href="#EtabPublic">Etablissement public</a></li>
						<li><a href="#Mairie">Mairie </a></li>
						<li><a href="#Région" class="ui-btn-active ui-state-persist">Région</a></li>
						<li><a href="#Département">Département</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Région" />
				
				<div data-role="fieldcontain" class="mySlider">
					<label for="flip-a2">Vous êtes:</label>
					<select data-theme="b" name="activity" id="flip-a2" data-role="slider"
						onchange="">
						<option value="cr">Conseil Régional</option>
						<option value="pr">Préfecture de Région</option>
					</select>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup"  class="myCheck">
						<input type="radio" name="name" id="radio-view-a" value="PACA" /> <label for="radio-view-a">PACA</label>
						<input type="radio" name="name" id="radio-view-e" value="Rhône-Alpes" /><label for="radio-view-e">Rhône-Alpes</label>
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
						<label for="textinputu5"> Email: </label> <input id="textinputu5" name="email" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu6"> Téléphone: </label> <input id="textinputu6" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
				</div>
			</form>
		</div>
	</div>
	<div data-role="page" id="Département">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-mini="true"  data-iconpos="left">
				<ul>
					<li><a href="option" data-icon="back">Retour</a></li>
					<li><a data-icon="check" data-theme="b" onclick="$('#updateExtendedForm<?= ++$i ?>').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h2 style="text-align: center;">Profil myEurope</h2>
			<form action="updateExtended" method="post" id="updateExtendedForm<?= $i ?>" >

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['new'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="navbar">
					<ul>
						<li><a href="#Association">Association</a></li>
						<li><a href="#Entreprise">Entreprise</a></li>
						<li><a href="#EtabPublic">Etablissement public</a></li>
						<li><a href="#Mairie">Mairie </a></li>
						<li><a href="#Région">Région</a></li>
						<li><a href="#Département" class="ui-btn-active ui-state-persist">Département</a></li>
					</ul>
				</div>
				<input type="hidden" name="role" value="Département" />
				
				<div data-role="fieldcontain" class="mySlider">
					<label for="flip-a2">Vous êtes:</label>
					<select data-theme="b" name="activity" id="flip-a2" data-role="slider"
						onchange="">
						<option value="cg">Conseil Général</option>
						<option value="pd">Préfecture de Département</option>
					</select>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup" class="myCheck">
	
						<input type="radio" name="name" id="radio-view-a" value="Alpes-Maritimes" /> <label for="radio-view-a">Alpes-Maritimes</label>
						<input type="radio" name="name" id="radio-view-b" value="Alpes de Haute-Provence" /> <label for="radio-view-b">Alpes de Haute-Provence</label>
						<input type="radio" name="name" id="radio-view-c" value="Hautes-Alpes" /> <label for="radio-view-c">Hautes-Alpes</label>
						<input type="radio" name="name" id="radio-view-d" value="Savoie" /> <label for="radio-view-d">Savoie</label>
						<input type="radio" name="name" id="radio-view-e" value="Haute-Savoie" /><label for="radio-view-e">Haute-Savoie</label>
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputut4"> Adresse: </label> <input id="textinputut4" name="address" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu5"> Email: </label> <input id="textinputu5" name="email" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu6"> Téléphone: </label> <input id="textinputu6" name="phone" placeholder="" value=''
							type="email" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
				</div>
			</form>
		</div>
	</div>
</body>
</html>
