<!-- ------------------ -->
<!-- App Login View     -->
<!-- ------------------ -->


<!-- Page View     -->
<div data-role="page" id="loginView" >


	<!-- Header bar -->
	<? $title = _("Sign in");
       print_header_bar("?action=main", false, $title); ?>

       
	<!-- Page content -->
	<div data-role="content" class="content">
		
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error);?>
	
 		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Why Register ?") ?></h3>
			<p><?= _("Here you can login with your myMed profile, sign in using a social network or register to the myMed netowrk") ?></p>
		</div>
		
		<!-- Login form -->
		<form action="?action=login" method="post" data-ajax="false">
			
			<!-- Sign in with an account -->
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Login") ?></h3>
					<input type="hidden" name="signin" value="1" />
					<div style="text-align: left;">Email :</div>
					<input type="text" name="login" id="login" data-theme="c"/>
				    <div style="text-align: left;"><?= _("Password")?> :</div>
				    <input type="password" name="password" id="password" data-inline="true"  data-theme="c"/>  
				    <br />
				    <div data-role="controlgroup" data-type="horizontal">
				 	    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Sign in") ?>" />
						<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" data-icon="star"><?= _("Sign in with") ?></a>
						<a href="?action=register&method=showRegisterView" data-role="button" data-inline="true" data-mini="true" data-icon="pencil" data-iconpos="right"><?= _("Register") ?></a>	
					</div>
			    </div>
			</div>
			
			<!-- Sign in with social network pop up -->
			<div data-role="popup" id="signinPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
				<ul data-role="listview">
				<li>
					<a href='/lib/socialNetworkAPIs/google/examples/simple/oauth_try.php' title='Google OAuth' rel='external'>
					<img class="ui-li-mymed" src="/system/img/social/google_32.png" />
					Google</a>
				</li>
				<li>
					<a href='/lib/socialNetworkAPIs/facebook/examples/oauth_try.php' title='Facebook OAuth' rel='external'>
						<img class="ui-li-mymed" src="/system/img/social/facebook_32.png" />
					Facebook</a>
				</li>
				<li>
					<a href='/lib/socialNetworkAPIs/twitter/redirect.php' title='Twitter OAuth' rel='external'>
					<img class="ui-li-mymed" src="/system/img/social/twitter_32.png" />
					Twitter</a>
				</li>
				<!--
				<li>
					<a onclick="$('#openIdForm').submit();" title="OpenID">
					<img class="ui-li-mymed" src="/system/img/social/openID_32.png" />
					<form onclick='event.stopPropagation();/* for clicking above and below thetext input without submitting*/' style='padding:8px 0; margin: -15px 0;' id='openIdForm' action='/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php' data-ajax='false'>
						<input id="openIdProvider" type="text"  name="openid_identifier" value="https://www.google.com/accounts/o8/id" placeholder="" />
					</form>
					</a>
				</li>
				-->
				</ul>
			</div>
	
		</form>

	</div> <!-- END page content -->
	
</div> <!-- END page -->
