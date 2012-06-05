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
	 *  you can update your profile
	 *  you can unsubscribe this user to this application and some of your predicate that you subscribed earlier
	 *  
	 *  ex: yourPC/application/myEurope/option?application=myTemplate shows your profile and your subscription for myTemplate app

	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myEurope";
	
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
					<a href="search" data-icon="arrow-l" data-transition="slide" data-direction="reverse"> Retour </a>
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
							<form action="controller" id="deconnectForm" data-ajax="false">
								<input name="logout" value="" type="hidden" />
							</form>
							<a href="" type="button" data-mini="true" data-icon="delete"
							style="width: 200px; margin-right: auto; margin-left: auto;" onclick="$('#deconnectForm').submit();">Déconnecter</a>
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
								foreach( $subscriptions as $k => $value ){ 
									$a = preg_split("/(nom|lib|cout|montant|date)/", $k ,0, PREG_SPLIT_DELIM_CAPTURE);
									$s = array();
									for ($i=1, $n=count($a)-1; $i<$n; $i+=2) {
										$s[$a[$i]] = $a[$i+1];
									}
							?>
							<li><a href=""> <?= json_encode($s); ?>
								<form action="controller" method="post" id="deleteSubscriptionForm<?= $k ?>">
									<input name="application" value='<?= $application ?>' type="hidden" />
									<input name="method" value='unsubscribe' type="hidden" />
									<input name="predicate" value=<?= $k ?> type="hidden" />
									<input name="userID" value='<?= $_SESSION['user']->id ?>' type="hidden" />
								</form> <a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $k ?>').submit();">Désabonnement</a>
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
