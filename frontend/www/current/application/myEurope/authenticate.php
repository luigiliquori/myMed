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
	$request->addArgument("application", $_GET['application']);

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
				unset($_SESSION['redirect']);
			} else {
				header("Location: ./updateExtended?new");
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
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c"  data-iconpos="left">
			<ul>
				<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete" data-iconpos="notext">myMed</a></li>
				<li><a href="about" data-icon="info"  data-transition="slidefade" data-direction="reverse"><?= _('About') ?></a></li>
				<li><a href="" data-icon="home"  class="ui-btn-active ui-state-persist"><?= _('Home') ?></a></li>
				<li><a href="register" data-transition="flip">inscription via myMed</a></li>
			</ul>
		</div>
	</div>
	<div data-role="content" style='text-align: center;'>
		
		<?= $msg ?>
		<h1 style="text-align:center;">
			<a href="./" style="text-decoration: none;"><?= Template::APPLICATION_NAME ?></a>
		</h1>
		<br />
		<form action="authenticate" method="post" id="loginForm" data-ajax="false">

			<input name="login" placeholder="email" value="" type="text" /><br />
			<input name="password" placeholder="Mot de passe" value="" type="password" /><br />
			
			<div style="text-align: center;" >
				<input type="submit"  data-inline="true" data-theme="b" value="Connexion"/><br />
			</div>
		</form>
	</div>
</div>
</body>
</html>
