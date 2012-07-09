<?php

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init(false);
if (isset($_SESSION['user'])){ // we should not be there
	header("Location: ./");
}

$msg = ""; //feedback text

if(isset($_GET['registration'])) { // registration account validation
	$request = new Request("v2/AuthenticationRequestHandler", CREATE);
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

	$request = new Request("v2/AuthenticationRequestHandler", READ);
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

			$extProfile = Template::fetchExtProfile();
			if($extProfile->status == 200 ) {
				foreach ($extProfile->dataObject->details as $v){
					if ($v->key == "type")
						$_SESSION['userType'] = $v->value;
					else if ($v->key == "perm")
						$_SESSION['userPerm'] = $v->value;
				};
				header("Location: ".(isset($_SESSION['redirect'])?$_SESSION['redirect']:"./index"));
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
	<div data-role="header" data-theme="c" style="max-height: 38px;">
		<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
			<a href="register" type="button" data-inline="true" data-transition="flip" style="top:2px;">inscription</a>
		</div>
	</div>
	<div data-role="content" style='text-align: center;'>
		
		<?= $msg ?>
		<h1><?= Template::APPLICATION_NAME ?></h1>
		<?php echo $lang['title']; ?>
		<br />
		<form action="authenticate" method="post" id="loginForm" data-ajax="false">

			<input name="login" placeholder="email" value="" type="text" /><br />
			<input name="password" placeholder="Mot de passe" value="" type="password" /><br />
			<div style="text-align: center;" >
				<input type="submit" data-theme="b" data-inline="true" value="Connexion"/>
			</div>
		</form>
	</div>
	<?= Template::credits(); ?>
</div>
</body>
</html>
