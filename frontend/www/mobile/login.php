<?php 
	$error = false;
	$success = false;
	if(isset($_POST['inscription'])) {
		if($_POST['password'] != $_POST['confirm']){
			$error = "FAIL: inscription != confirmation";
		}
		if($_POST['password'] == ""){
			$error = "FAIL: password cannot be empty!";
		}
		if($_POST['email'] == ""){
			$error = "FAIL: email cannot be empty!";
		}
		$error = file_get_contents(trim("http://mymed2.sophia.inria.fr:8080/mymed_backend/AuthenticationRequestHandler?code=1&login=".$_POST['email']."&password=".$_POST['password']));
	}
?>

<div id="login" data-role="page" data-fullscreen="true" data-theme="a">
	
	<!-- HEADER -->
	<div id="header" data-role="header">
		<h1>Welcome</h1>
	</div>

	<!-- CONTENT -->
	<div id="content" data-role="content" id="one" >
		<div class="tabMask"></div>
		
		<!-- NOTIFICATION -->
		<?php if($error) { ?>
			<div style="color: red;">
				<?= $error ?>
			</div>
		<?php }?>
		
		<img alt="mymed" src="img/logo-mymed.png">
		<br />
	    <span>eMail:</span>
	    <input type="text" name="name" id="name" value="" /><br />
	    <span for="password">Password:</span>
	    <input type="password" name="name" id="name" value="" />
		<br />
		
		<a href="#home" data-role="button" data-inline="true">Connexion</a>
		<a href="#inscription" data-role="button" data-inline="true" data-rel="dialog">Inscription</a>
		
		<div class="tabMask"></div>
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