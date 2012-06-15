<?php require_once '../lib/dasp/request/Request.class.php'; ?>
<?php require_once '../lib/dasp/handler/IRequestHandler.php'; ?>
<?php require_once '../lib/dasp/beans/MUserBean.class.php'; ?>
<?php require_once '../lib/dasp/beans/MAuthenticationBean.class.php'; ?>

<?php 
// Preconditions
if(!isset($_POST['login']) || !isset($_POST['password'])){
	echo '{
		"error": {
		"type": "InternalBackEndException",
		"status":  "500",
		"message": "wrong arguments"
		}
	}';
	return;
}

// DEBUG
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// AUTHENTICATION
$request = new Request("AuthenticationRequestHandler", READ);
$request->addArgument("login", $_POST["login"]);
$request->addArgument("password", $_POST["password"]);
$response = $request->send();

// Check if there's not error
$check = json_decode($response);
if(isset($check->error)) {
	echo $response;
} else {
	echo '{
		"success": {
			"status":  "200",
			"message": "authentication ok"
		}
	}';
}
?>
