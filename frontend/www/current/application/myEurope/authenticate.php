<?php

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	$template->checkSession( false );
	
	$msg = ""; //feedback text
	
	if (count($_POST)) {
		require_once '../../lib/dasp/request/Request.class.php';
		require_once '../../system/config.php';
		
		
		if(!isset($_SESSION['user'])) {
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

					$request = new Request("FindRequestHandler", READ);
					$request->addArgument("application", "myEurope");
					$request->addArgument("predicate", "ext".$_SESSION['user']->id);
					$responsejSon = $request->send();
					$extProfile = json_decode($responsejSon);
					if($extProfile->status == 200 && count($extProfile->dataObject->results) > 0 ) {
						$_SESSION['user']->type = $extProfile->dataObject->results[0]->data;
						$_SESSION['user']->permission = $extProfile->dataObject->results[0]->end;
						header("Location: ./");
					} else {
						header("Location: ./update?extended");
					}
					
				} else {
					$msg = $responseObject->description;
				}
	
			} else{
				$msg = $responseObject->description;
			}
		} else{
			header("Location: ./option?please-logout-first");
		}
		

	}
		
	
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>
		<div data-role="page" id="Authenticate">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<h3>myEurope - login</h3>
				</div>
				<div data-role="content">
					<div style='color:Red;text-align:center;'><?= $msg ?></div>
					<form action="#" method="post" id="loginForm">
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputl1"> email: </label> <input id="textinputl1"  name="login" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputl2"> Mot de passe: </label> <input id="textinputl2"  name="password" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<a href="" type="submit" onclick="$('#loginForm').submit();">Connecter</a>
						<a href="register" type="button" data-transition="flip">Créer un compte</a>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>