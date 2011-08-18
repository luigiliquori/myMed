<!-- login -->
<div id="login" data-role="page" data-fullscreen="true" data-theme="a">
	
	<!-- CONTENT -->
	<div id="content" data-role="content" id="one" >
		
		<!-- LOGO -->
		<img alt="mymed" src="img/logo-mymed.png" width="200px;">
		<br />
		
		<!-- NOTIFICATION -->
		<?php if($loginHandler->getError()) { ?>
			<div style="color: red;">
				<?= $loginHandler->getError(); ?>
			</div>
		<?php } else if($loginHandler->getSuccess()) { ?>
			<div style="color: #12ff00;">
				<?= $loginHandler->getSuccess(); ?>
			</div>
		<?php } ?>
		
		<br />
		<form action="#" method="post" name="singinForm" id="singinForm">
			<input type="hidden" name="singin" value="1" />
		    <span>eMail:</span>
		    <input type="text" name="login" id="login" value="" /><br />
		    <span>Password:</span>
		    <input type="password" name="password" id="password" value="" />
			<br />
			<a href="#home" data-role="button" data-inline="true" onclick="document.singinForm.submit()">Connexion</a>
			<a href="#inscription" data-role="button" data-inline="true" data-rel="dialog">Inscription</a>
		</form>
		
	</div>
	
	<!-- FOOTER_PERSITENT-->
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" class="ui-btn-active">Login</a></li>
				<li><a href="#privacy">Privacy</a></li>
				<li><a href="http://www.mymed.fr">About us</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->
	
</div>