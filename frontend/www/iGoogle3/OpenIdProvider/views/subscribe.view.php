<?php 
$date = date('Y-m-d', time()-3600*24*365*30);
?>
				<form action="<?=$this->httpScriptPath.'/'.PAGE_SUBSCRIBE?>" method="post">
					<div>
						<div>
							<label for="login">Nom d'utilisateur&nbsp;:</label>
							<input id="login" name="login" required="required" />
						</div>
						<div>
							<label for="password">Mot de Passe&nbsp;:</label>
							<input id="password" name="password" type="password" required="required" />
						</div>
						<div>
							<label for="password2">Retapez le Mot de Passe&nbsp;:</label>
							<input id="password2" name="password2" type="password" required="required" />
						</div>
						<div>
							<label for="email">E-mail&nbsp;:</label>
							<input id="email" name="email" type="email" required="required" />
						</div>
						<div>
							<label for="city">Ville&nbsp;:</label>
							<input id="city" name="city" />
						</div>
						<div>
							<label for="dob">Date de Naissance&nbsp;:</label>
							<input id="dob" name="dob" value="<?=$date?>" placeholder="<?=$date?>" type="date" />
						</div>
					</div>
					<button type="submit"><span>S'inscrire</span></button>
				</form>