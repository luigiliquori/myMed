<!-- ------------------ -->
<!-- App Login View     -->
<!-- ------------------ -->

<? require_once("header.php"); ?>

<div data-role="page" id="loginView" >

	<!-- Header bar -->
	<? include "header-bar.php";
	   $title = _("Connect");
       print_header_bar("?action=main", false, $title); ?>
	
	<!-- Page content -->
	<div data-role="content" class="content">
		
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error);?>
	
 		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true" style="margin:15px">
			<h3><?= _("Why Register ?") ?></h3>
			<p><?= _("By registering to myRiviera you will be able to ....") ?></p>
			<p><?= _("Don't forget that you have the possibility to use your social network accounts to create your profile: see button")?> "<?= _("Connect with") ?>"</p>
		</div>
		
		<form action="?action=login" method="post" data-ajax="false">
			
			<!-- Sign in with an account -->
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true" style="margin:15px">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Connection") ?></h3>
					<input type="hidden" name="signin" value="1" />
					<div style="text-align: left;"><?= _("E-mail")?><b>*</b> :</div>
					<input type="text" name="login" id="login" data-theme="c"/>
				    <div style="text-align: left;"><?= _("Password")?><b>*</b> :</div>
				    <input type="password" name="password" id="password" data-inline="true"  data-theme="c"/>  
				    <p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
				    <div data-role="controlgroup" data-type="horizontal">
				 	    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Connect") ?>" />
						<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" data-icon="star"><?= _("Connect with") ?></a>
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
				</ul>
			</div>
	
		</form>

	</div> <!-- END page content -->
	
</div> <!-- END page -->
