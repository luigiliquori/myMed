<?php

require_once("header.php");

function tab_bar_login($activeTab) {
	if(!function_exists('tabs')) {
		function tabs($activeTab, $tabs, $opts = false){
			return tabs_default($activeTab, $tabs, $opts);
		}
	}
	tabs($activeTab, array(
		array("#login", "Sign in", "signin"),
		array("#register", "Create an account", "th-list"),
		array("#about", "About", "info-sign")
	));
	include 'social.php';
	if (!isset($_SESSION['user'])):
	?>
		<a href="?action=guest&method=read" rel="external" class="ui-btn-right" style="top:-36px;left: 5px;" data-role="button" data-mini="true" data-theme="g"><?= _("Tour") ?></a>
	<? endif;
}
?>

<!-- INCLUDE THE MAIN PAGE OF THE PROJECT -->
<?php include('mymed.php'); ?>

<!-- Login page for betaUsers -->
<div data-role="page" id="login">

	<?php tab_bar_login("#login"); ?>
	<?php include('notifications.php'); ?>
	
	<div data-role="content" class="content">
	
	<? if (APPLICATION_NAME == 'myMed'): ?>
		<img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="width: 200px; margin-top: -15px;" />
		<br>
	<? else: ?>
	 	<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
	<? endif; ?>
		<br>
		<form action="?action=login" method="post" data-ajax="false">
			<input type="hidden" name="signin" value="1" />
			<input type="text" name="login" id="login" placeholder="Email" data-theme="c"/>
		    <input type="password" name="password" id="password" data-inline="true" placeholder="<?= _("Password") ?>"  data-theme="c"/>  
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Sign in") ?>" />
			
			<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true"><?= _("Sign in with") ?></a>
		</form>

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


		<br><br>
		<a href="http://www.interreg-alcotra.org/2007-2013/index.php?pg=progetto&id=139"><img alt="Alcotra" src="/system/img/logos/alcotra.png" /></a>
		<br>
		<i style="font-size: 13px;">“Ensemble par-delà les frontières”</i>
		<br><br>
		<img alt="Alcotra" src="/system/img/logos/europe.jpg" />
		
	</div>
	
</div>

	
<div data-role="page" id="register">
	
	<?php tab_bar_login("#register"); ?>
	<?php include('notifications.php'); ?>
	
	<div data-role="content">
	
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
					<input type="submit" data-role="button" data-theme="b" data-inline="true" value="<?= _('Send') ?>" />
				</div>
		
		</form>
	</div>
	
</div>

<div data-role="page" id="about" >	

	<?php tab_bar_login("#about"); ?>
	<?php include('notifications.php'); ?>

	<div data-role="content" class="content">
	
		<a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>
		<h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>
		<br>
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
			<li>
			<p>
			<?= _(APPLICATION_NAME."about") ?>
			</p>
			</li>
		</ul>
		<p>
		<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
		</p>
		<br>
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>

</div>

<? include_once("footer.php"); ?>
