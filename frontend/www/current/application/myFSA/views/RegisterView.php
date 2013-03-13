
<? require_once("header.php"); ?>
</head>
<body>

<div data-role="page" id="register" >	
	
	<div data-role="header" data-theme="b" data-position="fixed">
		<h1 style="color: white;"><?= _("Register a new account")?></h1>
		<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
			<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/u/0/101253244628163302593/posts" title="myFSA on Google+"></a>
			<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/MyFSA/122386814581009" title="myFSA on Facebook"></a>
			<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/my_europe" title="myFSA on Twitter"></a>
		</span>
		
	</div>

	<div data-role="content">
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		<!--  Register form -->
		<form action="index.php?action=register" method="post" data-ajax="false">
		
				<label for="prenom"><?= _("First name")?> :</label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom"><?= _("Last name")?> :</label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" ><?= _("E-mail")?><b>*</b> :</label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" ><?= _("Password")?><b>*</b> : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" ><?= _("Password Confirmation")?><b>*</b> : </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 5px;width:17px;height:17px"/>
				<span style="position: relative; left: 50px;">
					J'accepte les 
					<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a> / 
					I accept 
					<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a>
				</span>
				<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
				<center>
					<input type="submit" data-role="button" data-theme="g" data-icon="ok" data-inline="true" value="<?= _('Send') ?>" />
				</center>
		
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid"  class="ui-btn-active ui-state-persist">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	
</div>

<? require_once("LoginView.php"); ?>

<? require_once("AboutView.php"); ?>

<? require_once("footer.php"); ?>
