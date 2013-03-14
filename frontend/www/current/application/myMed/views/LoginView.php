<?php

require_once("header.php");
require_once 'notifications.php';

function tab_bar_login($activeTab) {
	if(!function_exists('tabs')) {
		function tabs($activeTab, $tabs, $opts = false){
			return tabs_default($activeTab, $tabs, $opts);
		}
	}
	tabs($activeTab, array(
		array("#login", "Sign in", "signin"),
		array("?action=register&method=showRegisterView", "Create an account", "th-list"),
		array("?action=about&method=show_aboutView", "About", "info-sign")
	));
	//include 'social.php';
	if (!isset($_SESSION['user'])): ?>
	<!-- <a href="?action=guest&method=read" rel="external" class="ui-btn-right" style="top:-36px;left: 5px;" data-role="button" data-mini="true" data-theme="g"><?= _("Tour") ?></a> -->
	<? endif;
}
?>


<!-- Login page for betaUsers -->
<div data-role="page" id="login">

	<?php tab_bar_login("#login"); ?>
	
	<div data-role="content" class="content">
		<?
		
		print_notification($this->success.$this->error);?>
	
	<? if (APPLICATION_NAME == 'myMed'): ?>
		<img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="width: 200px; margin-top: -15px;" />
		<br>
	<? else: ?>
	 	<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
	<? endif; ?>
	
		<br>
		<form action="?action=login" method="post" data-ajax="false">
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Connection") ?></h3>
					<input type="hidden" name="signin" value="1" />
					
					<div style="text-align: left;"><?= _("E-mail")?><b>*</b> :</div>
					<input type="text" name="login" id="login" data-theme="c"/>
				    
				    <div style="text-align: left;"><?= _("Password")?><b>*</b> :</div>
				    <input type="password" name="password" id="password" data-inline="true" data-theme="c"/>  
		 		    
		 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" data-mini="true" data-icon="signin" value="<?= _("Sign in") ?>" />
					
					<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true"><?= _("Sign in with") ?></a>
					<a href="?action=guest&method=read" rel="external" class="ui-btn-right" data-role="button" data-mini="true" data-theme="g"><?= _("Tour") ?></a>
				</div>
			</div>
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
			<!-- 
			<li>
				<a onclick="$('#openIdForm').submit();" title="OpenID">
				<img class="ui-li-mymed" src="/system/img/social/openID_32.png" />
				<form onclick="event.stopPropagation();/* for clicking above and below thetext input without submitting*/" style="padding:8px 0; margin: -15px 0;" id="openIdForm" action="/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php" data-ajax="false">
					<input id="openIdProvider" type="text"  name="openid_identifier" value="https://www.google.com/accounts/o8/id" placeholder="" />
				</form>
				</a>
			</li>
			 -->
			</ul>
		</div>

		<br><br>
 	<!-- <p>
			<?= _("The myMed consortium is composed by INRIA, Nice Sophia-Antipolis University, Politecnico di Torino, Turin University, and Piémont Oriental University, and it is founded by:")?>
		</p>
		<?php //include(MYMED_ROOT . '/system/views/logos.php'); ?>
		<br>
		<i style="font-size: 13px;">“Ensemble par-delà les frontières”</i>
		<br><br> -->
		<!--<a href="http://www.interreg-alcotra.org/2007-2013/index.php?pg=progetto&id=139"><img alt="Alcotra" src="/system/img/logos/alcotra.png" /></a>-->
		<!--<img alt="Alcotra" src="/system/img/logos/europe.jpg" />-->
		
	</div>
	
</div>



<!-- INCLUDE THE MAIN PAGE OF THE PROJECT -->
<? include('mymed.php'); ?>

<? include_once("footer.php"); ?>
