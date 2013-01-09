<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="login">

	<? print_header_bar(true, false); ?>
	
	<div data-role="content" class="content">
		
		<? print_notification($this->success.$this->error); ?>
	
 		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Why Register ?") ?></h3>
			<p><?= _("Creating an account in myEurope will allow you to submit new projects and to participate in discussions in the topic \"Best Practices\".") ?></p>
			<p><?= _("Don't forget that you have the possibility to use your social network accounts to create your profile: see button")?> "<?= _("Sign in with") ?>"</p>
		</div>
		
		<form action="?action=login" method="post" data-ajax="false">
		
			<div style="text-align: left;">
				<div data-role="controlgroup" data-type="horizontal">
					<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" data-icon="star"><?= _("Sign in with") ?></a>
				 </div>
			</div>
		
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">
				
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Login") ?></h3>
				
					<input type="hidden" name="signin" value="1" />
					<div style="text-align: left;">Email :</div>
					<input type="text" name="login" id="login" data-theme="c"/>
				    <div style="text-align: left;">Password :</div>
				    <input type="password" name="password" id="password" data-inline="true"  data-theme="c"/>  
				    <br />
				    <div data-role="controlgroup" data-type="horizontal">
				 	    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Sign in") ?>" />
						<a href="#register" data-role="button" data-inline="true" data-mini="true" data-icon="pencil" data-iconpos="right"><?= _("Register") ?></a>
					</div>
				    
			    </div>
			</div>
			
			<div data-role="popup" id="signinPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
				<ul data-role="listview">
				<li>
					<a href="/lib/socialNetworkAPIs/google/examples/simple/oauth_try.php" title="Google OAuth" rel="external">
					<img class="ui-li-mymed" src="/system/img/social/google_32.png" />
					Google</a>
				</li>
				<li>
					<a href="/lib/socialNetworkAPIs/facebook/examples/oauth_try.php" title="Facebook OAuth" rel="external">
						<img class="ui-li-mymed" src="/system/img/social/facebook_32.png" />
					Facebook</a>
				</li>
				<li>
					<a href="/lib/socialNetworkAPIs/twitter/redirect.php" title="Twitter OAuth" rel="external">
					<img class="ui-li-mymed" src="/system/img/social/twitter_32.png" />
					Twitter</a>
				</li>
				<li>
					<a onclick="$('#openIdForm').submit();" title="OpenID">
					<img class="ui-li-mymed" src="/system/img/social/openID_32.png" />
					<form onclick="event.stopPropagation();/* for clicking above and below thetext input without submitting*/" style="padding:8px 0; margin: -15px 0;" id="openIdForm" action="/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php" data-ajax="false">
						<input id="openIdProvider" type="text"  name="openid_identifier" value="https://www.google.com/accounts/o8/id" placeholder="" />
					</form>
					</a>
					
				</li>
				</ul>
			</div>
			
		</form>

	</div>
	
</div>
	
<div data-role="page" id="register">
	
	<? print_header_bar(true, false); ?>
	
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!--  Register form -->
		<form action="?action=register" method="post" data-ajax="false">
		
				<label for="prenom"><?= _("First name") ?>: </label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom"><?= _("Last name") ?>: </label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" >Email: </label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" ><?= _("Password") ?>: </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" ><?= _("Confirm") ?>: </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
				<span style="position: relative; left: 50px;">
					<a href="application/myMed/doc/CGU_fr.pdf" rel="external"><?= _('I accept the general terms and conditions'); ?></a>
				</span><br>
				
				<div style="text-align: center;">
					<input type="submit" data-role="button" data-theme="g" data-inline="true" data-mini="true" data-icon="ok-sign" value="<?= _('Send') ?>" />
				</div>
		
		</form>
	</div>
	
</div>

