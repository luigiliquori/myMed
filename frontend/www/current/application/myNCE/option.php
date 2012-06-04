<!DOCTYPE html>
<html>

<?php

	/*
	 * usage:
	 *  option?application=val1&param2=val2
	 *  
	 * what it does:
	 *  displays your profile
	 *  
	 *  if param email is present, it will attempt a profile update
	 *  if param predicate is present it will attempt unsubscribe this user to this application and predicate
	 *  
	 *  ex: yourPC/application/myEurope/option?application=myTemplate shows your profile and your subscription for myTemplate app
	 *  
	 *  if you are not satisfied with this doc, do it yourself
	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();

	// DEBUG
	//require_once('PhpConsole.php');
	//PhpConsole::start();
	
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myEurope";
	
	if (isset($_POST['email'])){ //we update the profile
		require_once '../../lib/dasp/beans/MUserBean.class.php';
		require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';
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
			$responseObject->success = true;
		}
		
		$_SESSION['user'] = $responseObject->dataObject->profile;
	}
	
	if (isset($_REQUEST['predicate'])){ // unsubscribe
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", $_REQUEST['predicate']);
		$request->addArgument("userID", $_REQUEST['userID'] );
		if (isset($_REQUEST['accessToken']))
			$request->addArgument('accessToken', $_REQUEST['accessToken']);
		// ^  to be able to unsubscribe from emails to deconnected session but not deleted session (will fail in this case)
		// I will see with Laurent if we can remove the token check for unsubscribe DELETE handler
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	}
	
	
	$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_SESSION["user"]->id);
	$responsejSon = $request->send();
	$profile = json_decode($responsejSon);
	
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

	<head>
		<?= $template->head(); ?>
		<script type="text/javascript">
	    	$(".ui-slider-handle .ui-btn-inner").live("mouseup", function() {
		        // we can't update reputation from the profile
		        $("#slider-0").val(25).slider("refresh");
		    });
		</script>
	</head>


	<body>
	
		<div data-role="page" id="Home">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="search" data-icon="back" data-ajax="false"> Retour </a>
					<h2>myEurope - profil</h2>
				</div>
				<div data-role="content">
					<div style="position:relative;text-align: center;min-height: 280px;">
						<h3>Mon profil</h3>
						<?php 
						
						if($profile->status == 200) {
							$profile = $profile->dataObject->user;
							$profPic = ($profile->profilePicture) ? $profile->profilePicture : "http://graph.facebook.com//picture?type=large";
						?>
						<img style="float:right;text-align: center; max-height: 220px;opacity: 0.2;" src="<?= $profPic ?>" />
						<div style="width: 40%;opacity: 1; position:absolute;top:30%;left:30%;z-index:1">
							 nom:
							<?= $profile->firstName ?>
							<br /> prénom:
							<?= $profile->lastName ?>
							<br /> date de naissance:
							<?= $profile->birthday ?>
							<br />
							Réputation: <input type="range" name="slider" id="slider-0" value="25" min="0" max="100" data-highlight="true" data-mini="true" />
							<br /><br />
							<a href="update" type="button" data-transition="flip" data-mini="true" data-icon="grid"
							style="width: 200px; margin-right: auto; margin-left: auto;">Modifier</a>
						</div>
						​
	
					<?php 
					}
				?>
					</div>
					
					<hr />
					<div style="text-align: center;">
						<h3>Mes souscriptions (<?= $totalSub ?>)</h3>
						<ul data-role="listview" data-inset="true" data-filter-placeholder="...">
							<?php 
							if($subscriptions->status == 200) {
								$subscriptions = (array) $subscriptions->dataObject->subscriptions;
								foreach( $subscriptions as $i => $value ){ 
							?>
							<li><a href=""> <?= $i ?>
								<form action="#" method="post" id="deleteSubscriptionForm<?= $i ?>">
									<input name="application" value="<?= $application ?>" type="hidden" /> <input name="predicate" value=<?= $i ?> type="hidden" />
									<input name="userID" value="<?= $_SESSION['user']->id ?>" type="hidden" />
								</form> <a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $i ?>').submit();">Désabonnement</a>
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
