<div id="inscription" data-role="page">
		<!-- HEADER -->
		<div id="header" data-role="header" data-theme="b" >
			<h2>Create an account </h2>
		</div>
	
		<!-- CONTENT -->
		<div id="content" data-role="content" id="one" data-theme="b">
			<form action="#login" method="post" name="inscriptionForm" id="inscriptionForm">
				<input type="hidden" name="inscription" value="1" />
				<span>Prénom : </span><input type="text" name="prenom" /><br />
				<span>Nom : </span><input type="text" name="nom" /><br />
				<span>eMail : </span><input type="text" name="email" /><br />
				<span>Password : </span><input type="password" name="password" /><br />
				<span>Confirm : </span><input type="password" name="confirm" /><br />
				<span>Date de naissance : </span><input type="text" name="birthday" /><br />
				<input type="checkbox" name="checkCondition" /><span>J'accepte les <a href="#">conditions d'utilisation</a></span><br />
				<a href="#" data-role="button" onclick="document.inscriptionForm.submit()">Create</a>
			</form>
		</div>
</div>