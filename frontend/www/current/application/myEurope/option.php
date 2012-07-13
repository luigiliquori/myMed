<?php

/*
*  displays your profile
*
*  -> update your profile
*  -> quit to mymed page
*  -> unsubscribe to one of your subscriptions
*
*/


//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();

$msg = ""; //feedback text

if (isset($_POST['predicate'])){ // unsubscribe
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
	unset($_SESSION['redirect']);
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		header("Location: ./");
	}
	//header("Location: http://".$_SERVER['HTTP_HOST']); // go back to mymed
}

//not necessary it's already in session
$request = new Request("ProfileRequestHandler", READ);
$request->addArgument("id", $_SESSION["user"]->id);
$responsejSon = $request->send();
$profile = json_decode($responsejSon);
if($profile->status == 200) {
	$_SESSION['user'] = $profile->dataObject->user;
}

$totalSub = 0;
if (!isset($_GET["application"])){
	$request = new Request("SubscribeRequestHandler", READ);
	$request->addArgument("application", Template::APPLICATION_NAME.":part");
	$request->addArgument("userID", $_SESSION['user']->id);
	$responsejSon = $request->send();
	$subscriptions = json_decode($responsejSon);
	if($subscriptions->status == 200) {
		$subscriptions = (array) $subscriptions->dataObject->subscriptions;
		$totalSub += count($subscriptions);
	}
} else {
	$request = new Request("SubscribeRequestHandler", READ);
	$request->addArgument("application", $_GET["application"]);
	$request->addArgument("userID", $_SESSION['user']->id);
	$responsejSon = $request->send();
	$subscriptionspart = json_decode($responsejSon);
	if($subscriptionspart->status == 200) {
		$subscriptionspart = (array) $subscriptionspart->dataObject->subscriptions;
		$totalSub += count($subscriptionspart);
	}
}




	

?>

<!DOCTYPE html>
<html>

<head>
<?= Template::head(); ?>
</head>


<body>

	<div data-role="page" id="Home">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-iconpos="left">
				<ul>
					<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete">myMed</a></li>
					<li><a href="about" data-icon="info" data-transition="slidefade" data-direction="reverse"><?= _('About') ?></a></li>
					<li><a href="./" data-icon="home" data-transition="slidefade" data-direction="reverse"><?= _('Home') ?></a></li>
					<li><a href="option" data-icon="profile" data-transition="slidefade" class="ui-btn-active ui-state-persist" ><?= _('Profil') ?></a></li>
				</ul>
			</div>
		</div>

		<div data-role="content" style="text-align: center;">
		
			<span style='color: lightGreen;'><?= $msg ?></span>
			
			<h3 style="text-align:center;">
				<a href="" style="text-decoration: none;">Mon profil</a>
			</h3>
			<?php 
			$profPic = ($_SESSION["user"]->profilePicture) ? $_SESSION["user"]->profilePicture : "http://graph.facebook.com//picture?type=large";
			$perms = array( 0 => "Utilisateur", 1 => "Modérateur", 2 => "Modérateur Européen");
			?>
			<img style="text-align: center; max-height: 120px; " src="<?= $profPic ?>" /><br />
			
			<div style="text-align: left; max-width:400px; margin:auto;">
			
				Type d'institution représentée: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $_SESSION["userType"] ?> </span> <br />
				Niveau de permission:	<span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $perms[$_SESSION["userPerm"]] ?> </span> <br /> <br />
				nom: <span	style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $_SESSION["user"]->name ?> </span> <br />
				email: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $_SESSION["user"]->email ?> </span> <br /> <br />
				
				<div style="text-align: center;" >
					<a href="update" type="button" data-inline="true" data-transition="flip" data-mini="true">Modifier son profil myMed</a><br />
					<a href="updateExtended" type="button" data-inline="true" rel="external" data-transition="flip" data-mini="true" >Modifier son profil myEurope</a><br />
					
					<a type="button" data-mini="true" data-icon="delete" data-inline="true"
						onclick="$('#deconnectForm').submit();">Déconnecter</a>
				</div>
				<form action="option" id="deconnectForm" data-ajax="false">
					<input name="logout" type="hidden" />
				</form>
			</div>
		

			<hr />
			
			<h3>
				<a href="" style="text-decoration: none;">Mes souscriptions (<?= $totalSub ?>)</a>
			</h3>
			<ul data-role="listview" data-inset="true" data-filter-placeholder="...">
				<?php 
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
							<input name="application" value='<?= Template::APPLICATION_NAME."part" ?>' type="hidden" /> <input name="predicate" value=<?= $k ?> type="hidden" /> <input
								name="userID" value='<?= $_SESSION['user']->id ?>' type="hidden" />
						</form> <a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $i++ ?>').submit();">Désabonnement</a>
				</a>
				</li>
				<?php 
				}
				?>
			</ul>

		</div>
		<?php 
		if ($_SESSION['userPerm']>0){
		?>
		<div data-role="footer" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-iconpos="left">
				<ul>
					<li><a href="admin" data-icon="gear" data-transition="slidefade">Admin</a></li>
				</ul>
			</div>
		</div>
		
		<?php 
		}
		?>
	</div>
</body>
</html>
