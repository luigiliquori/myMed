<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>

<div data-role="page" id="login">
	
	<div data-role="header" data-theme="b" data-position="fixed">
	
	<h1 style="color: white;"><?= APPLICATION_NAME ?> - Réseau Social Transfrontalier </h1>	
	<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
		<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/u/0/101253244628163302593/posts" title="myFSA on Google+"></a>
		<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/MyFSA/122386814581009" title="myFSA on Facebook"></a>
		<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/my_europe" title="myFSA on Twitter"></a>
	</span>
	<? include("notifications.php")?>
	</div>
	
	<div data-role="content"  class="content">
	
		<form action="?action=login" method="post" data-ajax="false" style="position: relative; top: 120px; height: 400px;">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="Mot de passe"  data-theme="c"/><br />
 		    <input type="submit" data-role="button" data-inline="true" data-theme="b" value="Connexion" />
		</form>

		<div data-role="collapsible" data-mini="true" data-collapsed-icon="twitter" data-content-theme="d" data-inline="true" style="position: relative; top: -50px; width:70%; margin: auto;">
			<h3 style="width: 170px; margin: auto;"><?= translate("Sign in with") ?>: </h3>
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
			</ul>
		</div>

		<br /><br />
		
			<img alt="Alcotra" src="../../system/img/logos/alcotra.png" />
			<br />
			<i>"Ensemble par-delà les frontières"</i>
			<br /><br />
			<img alt="Alcotra" src="../../system/img/logos/europe.jpg" />
		
		
	</div>
	
				
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	
</div>
	
<? require_once("RegisterView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>