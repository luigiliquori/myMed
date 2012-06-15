<? include("header.php"); ?>
<div  data-role="content" data-theme="a">

	<!-- Login form -->
	<form id="login" action="?action=login" method="post" data-ajax="false" class="tab" >

			<input type="text" name="login" placeholder="Login" />
			<input type="password" name="password" placeholder="Password" />
	
			<input type="submit" value="<?= _("Connection") ?>" data-theme="b" />
	
		</form>
		
	</div>
	

	<!--  Register form -->
	<a href="?action=register"  data-role="button"><?= _("Register") ?></a>

</div>
<? include("footer.php"); ?>