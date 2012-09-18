<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<?
function tab_bar_login($activeTab) {
	tabs($activeTab, array(
			array("#login", "Connexion", "signin"),
			array("#register", "Inscription", "th-list"),
			array("#about", "A propos", "info-sign")
	));
}
?>

<div data-role="page" id="login">

	
	<? tab_bar_login("#login"); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content"  class="content">

		<img alt="myMed" src="<?= MYMED_URL_ROOT ?>/application/myMed/img/logo-mymed-250c.png" width="200" />
		<form action="?action=login" method="post" data-ajax="false">
			<input type="hidden" name="signin" value="1" />
			<input type="text" name="login" id="login" placeholder="email"  data-theme="c"/>
		    <input type="password" name="password" id="password" data-inline="true" placeholder="<?= _("Password") ?>"  data-theme="c"/>
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" value="Connexion" />
			
		</form>
		
		
		<div data-role="collapsible" data-mini="true" data-collapsed="true" data-content-theme="c" data-inline="true" style="text-align: left;width: 260px;margin: auto;">
			<h3><?= _("Options") ?></h3>
			
			<h4><?= _("Connexion à partir de vos comptes:") ?></h4>
			(<a href="http://oauth.net/2/">OAuth</a>) <a  title="log in with Google Oauth" 
			href="<?= MYMED_URL_ROOT ?>/lib/socialNetworkAPIs/google/examples/simple/oauth_try.php" rel="external" style="background-position: -1px -1px" class="oauth_small_btn"></a>
			 <a  title="log in with Facebook Oauth" 
			href="javascript:alert('laurent?')" rel="external" style="background-position: -1px -105px" class="oauth_small_btn"></a>
			 <a  title="log in with Yahoo Oauth" 
			href="javascript:alert('...')" rel="external" style="background-position: -1px -27px" class="oauth_small_btn"></a><br />
			
			(<a href="http://openid.net/">OpenID</a>) <a  title="log in with Google openID" 
			href="<?= MYMED_URL_ROOT ?>/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php" rel="external" style="background-position: -1px -1px" class="oauth_small_btn"></a>
			  <a  title="log in with Facebook openID" 
			href="javascript:alert('')" rel="external" style="background-position: -1px -105px" class="oauth_small_btn"></a>
			 <a  title="log in with Yahoo openID" 
			href="javascript:alert('...')" rel="external" style="background-position: -1px -27px" class="oauth_small_btn"></a>
			 <a href="#oidFormPopup" data-rel="popup" data-role="none" >plus</a>
			 
			
			<div data-role="popup" id="oidFormPopup" class="ui-content" data-theme="d">
				<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<form action="<?= MYMED_URL_ROOT ?>/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_try.php" data-ajax="false">
					Url OpenID:
					<input type="text" style="width:280px;display:inline-block;" data-mini="true" name="openid_identifier" data-inline="true" value="https://www.google.com/accounts/o8/id" placeholder="Open ID url" />
					<div style="display:inline-block;vertical-align: middle;">
						<input type="submit" value="OK" title="log in with openid" data-mini="true" data-inline="true"/>
					</div>
				</form>
			</div>
			
		</div>



		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/alcotra.png" />
		<br />
		<i>"Ensemble par-delà les frontières"</i>
		<br /><br />
		<img alt="Alcotra" src="<?= MYMED_URL_ROOT ?>/system/img/logos/europe.jpg" />
		
	</div>
	

	
</div>
	
<? require_once("RegisterView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>