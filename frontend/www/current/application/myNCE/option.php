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
	 *  ex: yourPC/application/myNCE/option?application=myTemplate shows your profile and your subscription for myTemplate app
	 *  
	 *  if you are not satisfied with this doc, do it yourself
	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	$template->head();
	// DEBUG
	//require_once('PhpConsole.php');
	//PhpConsole::start();
	//debug('boo '.dirname(__FILE__));
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myNCE";
	
	if (isset($_REQUEST['email'])){ //we update the profile
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
?>

	<body>
		<div data-role="page" id="Option">
			<div data-role="header" data-theme="e">
				<a href="home.html" data-icon="back"> Back </a>
				<h2>myNCE Profil</h2>
			</div>
			<div data-role="content">

			<?php 
			
			if($profile->status == 200) {
				$profile = $profile->dataObject->user;
				$profPic = ($profile->profilePicture) ? $profile->profilePicture : "http://graph.facebook.com//picture?type=large";
				?>
			<div style="text-align: center; min-height: 220px;" >
				<h3>Mon profil</h3>
				<div style="background-image:url(<?= $profPic ?>);background-repeat:no-repeat;background-position:center right; background-size: contain;">
					nom:
					<?= $profile->firstName ?>
					<br /> prénom:
					<?= $profile->lastName ?>
					<br /> date de naissance:
					<?= $profile->birthday ?>
					<br /> 
					Réputation: <input type="range" name="slider" id="slider-0" value="25" min="0" max="100" data-highlight="true" data-mini="true" /><br /> <br />
				</div>

				<a href="#Update" type="button" data-transition="flip" data-mini="true" data-icon="grid"
					style="width: 200px; margin-right: auto; margin-left: auto;">Modifier</a>
			
			</div>
			<?php 
			}

			?>
				
				<hr />
				<div style="text-align: center">
					<h3>Mes souscriptions</h3>
					<ul data-role="listview" data-filter="true" data-inset="true">
						<?php 
						if($subscriptions->status == 200) {
							$subscriptions = $subscriptions->dataObject->subscriptions;
							foreach( $subscriptions as $i => $value ){ 
							?>
							<li><a href=""> <?= $value ?>
									<form action="#Option" method="post" id="deleteSubscriptionForm<?= $i ?>">
										<input name="application" value=<?= $application ?> type="hidden" /> <input name="predicate" value=<?= $value ?> type="hidden" />
										<input name="userID" value=<?= $_SESSION['user']->id ?> type="hidden" />
									</form> <a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $i ?>').submit();">Unsub me</a>
							</a>
							</li>
							<?php 
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div data-role="page" id="Update">
			<div data-role="header" data-theme="e">
				<a href="#Option" class="ui-btn-left" data-transition="flip" data-direction="reverse">Back</a>
				<h3>myNCE MaJProfil</h3>
			</div>
			<div data-role="content">
				<form action="#Option" method="post" id="updateForm">
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu1"> Prénom: </label> <input id="textinputu1"  name="prenom" placeholder="" value="<?= $profile->firstName ?>" type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu2"> Nom: </label> <input id="textinputu2"  name="nom" placeholder="" value="<?= $profile->lastName ?>" type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu3"> eMail: </label> <input id="textinputu3"  name="email" placeholder="" value="<?= $profile->email ?>" type="email" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu4"> Ancien Password: </label> <input id="textinputu4"  name="oldPassword" placeholder="" value="" type="password" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu5"> Nouveau Password: </label> <input id="textinputu5"  name="password" placeholder="" value="" type="password" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu6"> Date de naissance: </label> <input id="textinputu6"  name="birthday" placeholder="" value="<?= $profile->birthday ?>" type="date" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputu7"> Photo du profil (lien url): </label> <input id="textinputu7"  name="thumbnail" placeholder="" value="<?= $profile->profilePicture ?>" type="text" />
						</fieldset>
					</div>
					<a href="" type="button" data-icon="gear" onclick="$('#updateForm').submit();" style="width:100px; margin-right: auto; margin-left: auto;">Ok</a>
				</form>
			</div>
		</div>
	</body>
</html>
