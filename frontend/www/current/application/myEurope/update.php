<?php

/*
*  update your profile
*/

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();

$msg="";

if (isset($_POST['type'])) {
	//extended Profile (user's role)
	
	$permission = (
			strpos($_SESSION['user']->email, "@inria.fr") !== false ||
			$_SESSION['user']->email=="other@mail.com" )
			? 2 : 0;

	$data = array(
			array("key"=>"ext", "value"=>$_SESSION['user']->id, "ontologyID"=>0),
			array("key"=>"type", "value"=>$_POST['type'], "ontologyID"=>4),
			array("key"=>"perm", "value"=> $permission, "ontologyID"=>4 ),
	);
	$request = new Request("v2/PublishRequestHandler", UPDATE);
	$request->addArgument("application", Template::APPLICATION_NAME);

	$request->addArgument("data", json_encode($data));
	$request->addArgument("userID", $_SESSION['user']->id);
	$request->addArgument("id", "ext");
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status != 200) {
		$msg = $responseObject->description;
	} else {
		$_SESSION['userType'] = $_POST['type'];
		$_SESSION['userPerm'] = $permission;
		header("Location: ./");
	}
}

if (isset($_POST['email'])) { //profile update
	require_once '../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';

	// update the authentication
	$msg="";
	if ( ($_POST["newPassword"] !=  $_POST["newPasswordConf"]) && $_POST["newPassword"] != "" ){
		$msg = "mot de passes non identiques";
	} else if ($_POST["newPassword"] == "") {
		$msg = "";
	} else {
		$msg = "upd";
	}

	$mAuthenticationBean = new MAuthenticationBean();
	$mAuthenticationBean->login =  $_SESSION['user']->email;
	$mAuthenticationBean->user = $_SESSION['user']->id;
	$mAuthenticationBean->password = hash('sha512', ($msg == "upd")?$_POST["newPassword"]:$_POST["oldPassword"]);

	$request = new Request("AuthenticationRequestHandler", UPDATE);
	$request->addArgument("authentication", json_encode($mAuthenticationBean));

	$request->addArgument("oldLogin", $_SESSION['user']->email);
	$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	$msg = "";

	if($responseObject->status != 200) {
		$msg = $responseObject->description;
		//return;
	}
	if ($_POST["email"] == ""){
		$msg = "email vide";
	}

	if ($msg == ""){
		// update the profile
		$mUserBean = new MUserBean();
		$mUserBean->id = $_SESSION['user']->id;
		$mUserBean->firstName = $_POST["prenom"];
		$mUserBean->lastName = $_POST["nom"];
		$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
		$mUserBean->email = $_POST["email"];
		$mUserBean->login = $_POST["email"];
		$mUserBean->birthday = $_POST["birthday"];
		$mUserBean->profilePicture = $_POST["thumbnail"];

		// keep the session opened
		$mUserBean->socialNetworkName = $_SESSION['user']->socialNetworkName;
		$mUserBean->SocialNetworkID = $_SESSION['user']->socialNetworkID;
		$mUserBean->SocialNetworkID = $_SESSION['accessToken'];

		$request = new Request("ProfileRequestHandler", UPDATE);
		$request->addArgument("user", json_encode($mUserBean));

		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status != 200) {
			$msg = $responseObject->description;
		} else {
			header("Location: ./");
		}	
		//$_SESSION['user'] = $responseObject->dataObject->profile;
	}

}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>


<body>
	<div data-role="page" id="Update">
		<div class="wrapper">
			<div data-role="header" data-theme="c" style="max-height: 38px;">
				<a href="option" class="ui-btn-left" data-icon="back" data-transition="flip" data-direction="reverse"> Retour</a>
				<h2>
					<a href="./" style="text-decoration: none;">myEurope</a>
				</h2>
			</div>
			<div data-role="content">

				<form action="update" method="post" id="updateExtForm" >

					<div style='color: lightGreen; text-align: center;'>
						<?= $msg ?><?= isset($_GET['extended'])?" Veuillez compléter votre profil, pour l'utilisation de myEurope":""?>
					</div>

					<div data-role="fieldcontain">
						<fieldset id="test" data-role="controlgroup" data-type="horizontal" data-mini="true">
							<legend>
								Type d'institution:
								<?= isset($_GET['extended'])?" *":"" ?>
							</legend>
							<input type="radio" name="type" id="radio-view-a" value="Assoc/Entrp" <?= ($_SESSION['userType']=="Assoc/Entrp" ||isset($_GET['extended'])) ?"checked='checked'":"" ?> /> <label for="radio-view-a">Assoc/Entrp</label>
							<input type="radio" name="type" id="radio-view-b" value="Mairie" <?= $_SESSION['userType']=="Mairie"?"checked='checked'":"" ?> /> <label for="radio-view-b">Mairie</label>
							<input type="radio" name="type"id="radio-view-c" value="Com Urb" <?= $_SESSION['userType']=="Com Urb"?"checked='checked'":"" ?> /> <label for="radio-view-c">Com Urb</label>
							<input type="radio" name="type" id="radio-view-d" value="Etat" <?= $_SESSION['userType']=="Etat"?"checked='checked'":"" ?> /><label for="radio-view-d">Etat</label>
							<input type="radio" name="type" id="radio-view-e" value="Région" <?= $_SESSION['userType']=="Région"?"checked='checked'":"" ?> /> <label for="radio-view-e">Région</label>
						</fieldset>
						<a href="" type="button" data-icon="check" onclick="$('#updateExtForm').submit();" data-mini="true"
						style="position: absolute;top: -4px;right: 16%;" >Modifer</a>
					</div>
				</form>
				<?php 
				if (!isset($_GET['extended'])) {
				?>
				<form action="update" method="post" id="updateForm" >
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu1"> Prénom: </label> <input id="textinputu1" name="prenom" placeholder="" value='<?= $_SESSION['user']->firstName ?>'
								type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu2"> Nom: </label> <input id="textinputu2" name="nom" placeholder="" value='<?= $_SESSION['user']->lastName ?>'
								type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu3"> eMail: </label> <input id="textinputu3" name="email" placeholder="" value='<?= $_SESSION['user']->email ?>'
								type="email" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu4"> Mot de passe: </label> <input id="textinputu4" name="oldPassword" placeholder="" value="" type="password" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu51"> Changez de mot de passe?: (laissez vide sinon) </label> <input id="textinputu51" name="newPassword" placeholder="" value="" type="password" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu52"> Confirmation du nouveau mot de passe: </label> <input id="textinputu52" name="newPasswordConf" placeholder="" value="" type="password" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu6"> Date de naissance: </label> <input id="textinputu6" name="birthday" placeholder=""
								value='<?= $_SESSION['user']->birthday ?>' type="date" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu7"> Avatar (lien url): </label> <input id="textinputu7" name="thumbnail" placeholder=""
								value='<?= $_SESSION['user']->profilePicture ?>' type="text" />
						</fieldset>
					</div>
					
					<a href="" type="button" data-icon="gear" onclick="$('#updateForm').submit();" style="width: 121px; margin-right: auto; margin-left: auto;">Modifier</a>
				</form>
				<?php 
				}
				?>
				<div class="push"></div>
			</div>
		</div>
		<?= Template::credits(); ?>
	</div>
</body>
</html>
