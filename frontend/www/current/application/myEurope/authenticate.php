<?php

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init(false);

$msg = ""; //feedback text

if(isset($_GET['registration'])) { // registration account validation
	$request = new Request("AuthenticationRequestHandler", CREATE);
	$request->addArgument("accessToken", $_GET['accessToken']);

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status != 200) {
		$msg = "<span style='color: red; '>".$responseObject->description."</span>";
	} else {
		$msg = "<span style='color: lightgreen;'>Bienvenu sur myEurope, authentifiez-vous</span>";
	}
}

if (count($_POST)) {

	$request = new Request("AuthenticationRequestHandler", READ);
	$request->addArgument("login", trim(strtolower($_REQUEST["login"])));
	$request->addArgument("password", hash('sha512', $_REQUEST["password"]));

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
		//$_SESSION['user'] = $responseObject->dataObject->user;
		$request = new Request("SessionRequestHandler", READ);
		$request->addArgument("socialNetwork", "myMed");
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$_SESSION['user'] = $responseObject->dataObject->user;
			if(!isset($_SESSION['friends'])){
				$_SESSION['friends'] = array();
			}

			$request = new Request("v2/FindRequestHandler", READ);
			$request->addArgument("application", "myEurope");
			$request->addArgument("predicate", "ext");
			$request->addArgument("user", $_SESSION['user']->id);
			$responsejSon = $request->send();
			$extProfile = json_decode($responsejSon);
			if($extProfile->status == 200 ) {
				foreach ($extProfile->dataObject->details as $v){
					if ($v->key == "type")
						$_SESSION['userType'] = $v->value;
					else if ($v->key == "perm")
						$_SESSION['userPerm'] = $v->value;
				}
				header("Location: ./index?".(isset($_SESSION['redirect'])?$_SESSION['redirect']:""));
			} else {
				header("Location: ./update?extended");
			}
				
		} else {
			$msg = "<span style='color: red; '>".$responseObject->description."</span>";
		}

	} else{
		$msg = "<span style='color: red; '>".$responseObject->description."</span>";
	}



}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>
<div data-role="page" id="Authenticate">
	<div class="wrapper">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content" style='text-align: center;'>
			
			<?= $msg ?>
			<form action="authenticate" method="post" id="loginForm" data-ajax="false">
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputl1"> email: </label> <input id="textinputl1" name="login" placeholder="" value="" type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputl2"> Mot de passe: </label> <input id="textinputl2" name="password" placeholder="" value="" type="password" />
					</fieldset>
				</div>
				<a href="" type="submit" onclick="$('#loginForm').submit();">Connecter</a>
				<a href="register" type="button" data-transition="flip">Créer un compte</a>
			</form>
			<div class="push"></div>
		</div>
	</div>
	<?= Template::credits(); ?>
</div>
</body>
</html>
