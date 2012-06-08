<? include("header.php"); ?>
<div>
	<!--  Tabs -->
	<div data-role="navbar">
    <ul>
        <li><a href="#login"    data-href="a" data-click-on-load >Login</a></li>
        <li><a href="#register" data-href="b">Register</a></li>
    </ul>

	<!-- Login form -->
	<form id="login" action="index.php?action=login" method="post" data-ajax="false" class="tab" >
	
		<input type="text" name="login" placeholder="Login" />
		<input type="password" name="password" placeholder="Password" />
	
		<input type="submit" value="Connexion" />
	
	</form>
	
	<!--  Register form -->
	<form  id="register" action="index.php?action=register" method="post" data-ajax="false" class="tab" >
		<input type="submit" value="Register" />
	</form>
	
</div>
<? include("footer.php"); ?>