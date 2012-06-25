<?php

	/*
	 * usage:
	 *  option?application=val1&param2=val2
	 *  
	 * what it does:
	 *  displays your profile
	 *  
	 *  you can update your profile
	 *  you can unsubscribe this user to this application and some of your predicate that you subscribed earlier
	 *  
	 *  ex: yourPC/application/myEurope/option?application=myTemplate shows your profile and your subscription for myTemplate app

	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.php';
	$template = new Template();
	$template->checkSession();
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myEurope";

	$msg = ""; //feedback text
	
	if (isset($_POST['email'])) { //profile update
		require_once '../../lib/dasp/beans/MUserBean.class.php';
		require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';
		$responseObject = new stdClass();
		if($_POST['password'] == ""){
			$responseObject->error = "FAIL: password cannot be empty!";
			return;
		} else if($_POST['email'] == ""){
			$responseObject->error = "FAIL: email cannot be empty!";
			return;
		}
		// update the authentication
		$mAuthenticationBean = new MAuthenticationBean();
		$mAuthenticationBean->login =  $_SESSION['user']->email;
		$mAuthenticationBean->user = $_SESSION['user']->id;
		$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
		
		$request = new Request("AuthenticationRequestHandler", UPDATE);
		$request->addArgument("authentication", json_encode($mAuthenticationBean));
		
		$request->addArgument("oldLogin", $_SESSION['user']->email);
		$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));
		
		$responsejSon = $request->send();
		$responseObject1 = json_decode($responsejSon);
		
		if($responseObject1->status != 200) {
			echo json_encode($responseObject1);
			return;
		}
		
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
		
		if($responseObject->status == 200) {
			
		}
		$_SESSION['user'] = $responseObject->dataObject->profile;
		
		//extended Profile (user's role)
		$predicates = array(
				array("key"=>"ext", "value"=>$_SESSION['user']->id, "ontologyID"=>0)
		);
		
		$permission = ( $_SESSION['user']->email=="cyril.auburtin@gmail.com" ||
						  $_SESSION['user']->email=="other@gmail.com" )? 1 : 0;
		
		$_SESSION['userType'] = $_POST['type'];
		$_SESSION['userPerm'] = $permission;
		
		$data = array(
				array("key"=>"data", "value"=>$_POST['type'], "ontologyID"=>4),
				array("key"=>"end", "value"=> $permission, "ontologyID"=>4 ),
		);
		$request = new Request("PublishRequestHandler", CREATE);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", json_encode($predicates));
		$request->addArgument("data", json_encode($data));
		$request->addArgument("userID", $_SESSION['user']->id);
		$responsejSon = $request->send();

	} else if (isset($_POST['predicate'])){ // unsubscribe
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", $_REQUEST['application']);
		$request->addArgument("predicate", $_REQUEST['predicate']);
		$request->addArgument("userID", $_REQUEST['userID'] );
		if (isset($_REQUEST['accessToken']))
			$request->addArgument('accessToken', $_REQUEST['accessToken']);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if ($responseObject->status==200){
			header("Location: ./option");
		}
	}
	
	if(isset($_GET['registration'])) { // registration account validation
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $_GET['accessToken']);
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status != 200) {
			$msg = $responseObject->description;
		} else {
			header("Location: ./authenticate");
		}
		return;
	} else if (isset($_GET['userID'])){ // unsubscription by mail
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", $_GET['application']);
		$request->addArgument("predicate", $_GET['predicate']);
		$request->addArgument("userID", $_GET['userID'] );
		if (isset($_GET['accessToken']))
			$request->addArgument('accessToken', $_GET['accessToken']);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if ($responseObject->status==200){
			$msg = "Vous êtes désabonné de cette recherche";
		}
	}else if (isset($_GET['logout'])){ // deconnect
		$request = new Request("SessionRequestHandler", DELETE);
		$request->addArgument("accessToken", $_SESSION['user']->session);
		$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);
		
		session_destroy();
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			header("Location: http://".$_SERVER['HTTP_HOST']); // go back to mymed
			//header("Location: ./");
		}
	}
	
	//not necessary it's already in session
	/*$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_SESSION["user"]->id);
	$responsejSon = $request->send();
	$profile = json_decode($responsejSon);*/
	
	
	$request = new Request("SubscribeRequestHandler", READ);
	$request->addArgument("application", $application);
	$request->addArgument("userID", $_SESSION['user']->id);
	$responsejSon = $request->send();
	$subscriptions = json_decode($responsejSon);
	$totalSub = 0;
	if($subscriptions->status == 200) {
		$totalSub = count( (array) $subscriptions->dataObject->subscriptions);
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
					<a href="./" data-icon="home" data-iconpos="notext" data-transition="slide" data-direction="reverse"> Accueil </a>
					<h2>myEurope - profil</h2>
				</div>
				<div data-role="content">
					<div style="position:relative;text-align: center;min-height: 280px;">
						<div style='color:lightGreen;text-align:center;'><?= $msg ?></div>
						<h3>Mon profil</h3>
						<?php 
							$profPic = ($_SESSION["user"]->profilePicture) ? $_SESSION["user"]->profilePicture : "http://graph.facebook.com//picture?type=large";
							$perms = array( 0 => "Utilisateur", 1 => "Modérateur");
						?>
						<img style="float:right;text-align: center; max-height: 220px;opacity: 0.2;" src="<?= $profPic ?>" />
						<div style="width: 60%;opacity: 1; position:absolute;top:20%;left:20%;z-index:1">
							 
							  Type d'institution représentée:
							<span style="left-margin:5px; color: #0060AA; font-size:120%;"><?= $_SESSION["userType"] ?></span>
							<br />permission:
							<span style="left-margin:5px; color: #0060AA; font-size:120%;"><?= $perms[$_SESSION["userPerm"]] ?></span>
							<br />
							<br />nom:
							<span style="left-margin:5px; color: #0060AA; font-size:120%;"><?= $_SESSION["user"]->name ?></span>
							<br /> email:
							<span style="left-margin:5px; color: #0060AA; font-size:120%;"><?= $_SESSION["user"]->email ?></span>
							
							<br /><br />
							<a href="update" type="button" data-transition="flip" data-mini="true" data-icon="grid"
							style="width: 200px; margin-right: auto; margin-left: auto;">Modifier</a>
							<form action="option" id="deconnectForm" data-ajax="false">
								<input name="logout" type="hidden" />
							</form>
							<a href="" type="button" data-mini="true" data-icon="delete" data-ajax="false"
							style="width: 200px; margin-right: auto; margin-left: auto;" onclick="$('#deconnectForm').submit();">Déconnecter</a>
						</div>

					</div>
					
					<hr />
					<div style="text-align: center;">
						<h3>Mes souscriptions (<?= $totalSub ?>)</h3>
						<ul data-role="listview" data-inset="true" data-filter-placeholder="...">
							<?php 
							if($subscriptions->status == 200) {
								$subscriptions = (array) $subscriptions->dataObject->subscriptions;
								$i = 0;
								foreach( $subscriptions as $k => $value ){ 
									//prettify the subscription string:
									/*$a = preg_split("/(nom|lib|cout|montant|date)/", $k ,0, PREG_SPLIT_DELIM_CAPTURE);
									$s = array();
									for ($i=1, $n=count($a)-1; $i<$n; $i+=2) {
										$s[$a[$i]] = $a[$i+1];
									}*/
							?>
							<li><a href=""> <?= $k /*json_encode($s);*/ ?>
								<form action="#" method="post" id="deleteSubscriptionForm<?= $i ?>">
									<input name="application" value='<?= $application ?>' type="hidden" />
									<input name="predicate" value=<?= $k ?> type="hidden" />
									<input name="userID" value='<?= $_SESSION['user']->id ?>' type="hidden" />
								</form> <a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();">Désabonnement</a>
								</a>
							</li>
								<?php 
								}
							}
							?>
						</ul>
					</div>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
