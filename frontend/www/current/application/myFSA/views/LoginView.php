<? 

//
// This view shows both the login and register forms, with two tabs
//
require_once("header.php"); ?>
</head>
<body>

<div data-role="page" id="login">
	
	<div data-role="header" data-theme="b" data-position="fixed">
	
	<h1 style="color: white;">Réseau Social Transfrontalier </h1>	
	<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
		<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/u/0/101253244628163302593/posts" title="myFSA on Google+"></a>
		<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/MyFSA/122386814581009" title="myFSA on Facebook"></a>
		<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/my_europe" title="myFSA on Twitter"></a>
	</span>
	</div>
	
	<div data-role="content"  class="content">
		<br>
		<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		<br>
	
		<form action="?action=login" method="post" data-ajax="false">
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-mini="true">	
				<div data-role="collapsible" data-collapsed="false">
					<h3><?= _("Connection") ?></h3>
		
					<input type="hidden" name="signin" value="1" />
				    
				    <div style="text-align: left;"><?= _("E-mail")?><b>*</b> :</div>
				    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/>
				    
				    <div style="text-align: left;"><?= _("Password")?><b>*</b> :</div>
				    <input type="password" name="password" id="password" placeholder="Mot de passe"  data-theme="c"/>
		 		    
		 		    <input type="submit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="signin" value="<?= _("Connect") ?>" />
				</div>
			</div>
		</form>
		<div style="text-align: center">	
			<form action="?action=login" method="post" data-ajax="false">
				<input type="hidden" name="signin" value="1" />
			    <input type="hidden" name="login" id="login" value="guest_user@yopmail.com"/>
			    <input type="hidden" name="password" id="password" value="1"/>
	 		    <input type="submit" data-role="button" data-inline="true" data-mini="true" data-theme="g" data-icon="signin" value="<?= _("Guest")?>" />
			</form><br>
			<a href="#signinPopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" data-icon="star"><?= _("Connect with") ?></a>			
		</div>
		</br>

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