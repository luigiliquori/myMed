<!DOCTYPE html>
<html>

<?php
	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	$template->head();
	// DEBUG
	require_once('PhpConsole.php');
	PhpConsole::start();
	debug('boo '.dirname(__FILE__));
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_SESSION["user"]->id);
	$responsejSon = $request->send();
	$profile = json_decode($responsejSon);
	
	$request = new Request("SubscribeRequestHandler", READ);
	$request->addArgument("application", "myNCE");
	$request->addArgument("userID", $_SESSION['user']->id);
	
	$responsejSon = $request->send();
	$subscriptions = json_decode($responsejSon);
?>

	<body>
		<div data-role="page" id="Option">
			<div data-role="header" data-theme="e">
				<a href="home.html" data-icon="back"> Back </a>
				<h2>myNCE Profile</h2>
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
									<form action="unsubscribe.php" id="deleteSubscriptionForm<?= $i ?>">
										<input name="application" value="myNCE" type="hidden" /> <input name="predicate" value=<?= $value ?> type="hidden" />
										<input name="userID" value=<?= $_SESSION['user']->id ?> type="hidden" />
									</form> <a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $i ?>').submit();">Unsub</a>
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
				<h3>myNCE</h3>
			</div>
			<div data-role="content">
				<form action="update.php" id="updateForm">
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
					<a href="#Option" type="button" onclick="update();">Modifier</a>
				</form>
			</div>
		</div>
	</body>
</html>
