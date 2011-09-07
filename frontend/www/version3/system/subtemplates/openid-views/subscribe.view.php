<?php 
$defaultOld	= 30;
$minOld		= 6;
$date = date('Y-m-d', time()-3600*24*365*$defaultOld);
?>
			<form action="<?=ROOTPATH?>openid/subscribe" method="post">
				<div>
					<div>
						<label for="subscribe_login">Nom d'utilisateur</label>
						<input id="subscribe_login" name="login" required="required" />
					</div>
					<div>
						<label for="subscribe_password">Mot de Passe</label>
						<input id="subscribe_password" name="password" type="password" required="required" pattern="........+" title="Minimum 8 caractères" />
					</div>
					<div>
						<label for="subscribe_password2">Retapez le Mot de Passe</label>
						<input id="subscribe_password2" name="password2" type="password" required="required" />
						<script type="text/javascript">
						//<![CDATA[
						(function($){
							var password	= $("#subscribe_password")[0];
							$("#subscribe_password2").bind("input", function(){
								if(this.value	!== password.value)
									this.setCustomValidity('Les mots de passe ne sont pas identiques');
								else
									this.setCustomValidity(null);
							});
						})(jQuery);
						//]]>
						</script>
					</div>
					<div>
						<label for="subscribe_lastName">Nom</label>
						<input id="subscribe_lastName" name="lastName" type="text" required="required" />
					</div>
					<div>
						<label for="subscribe_firstName">Prénom</label>
						<input id="subscribe_firstName" name="firstName" type="text" required="required" />
					</div>
					<div>
						<label for="subscribe_email">Courriel</label>
						<input id="subscribe_email" name="email" type="email" required="required" />
					</div>
					<div>
						<label for="subscribe_gender">Sexe</label>
						<select id="subscribe_gender" name="gender">
							<option ></option>
							<option value="M">Homme</option>
							<option value="F">Femme</option>
						</select>
					</div>
					<div>
						<label for="subscribe_city">Ville</label>
						<input id="subscribe_city" name="city" />
					</div>
					<div>
						<label for="subscribe_dob">Date de Naissance</label>
						<input id="subscribe_dob" name="dob" placeholder="<?=$date?>" type="date" min="1880-01-01" max="<?=date('Y-m-d', time()-3600*24*365*$minOld)?>" />
						<script type="text/javascript">
							if(!jQuery.browser.opera&&jQuery.browser['version']>9.5)
								$("#subscribe_dob").dateinput({selectors:true, yearRange:[-131, -<?=$minOld-1?>]});
						</script>
					</div>
				</div>
				<button type="submit"><span>S'inscrire</span></button>
			</form>
<?php 
unset($defaultOld);
unset($minOld);
unset($date);
?>
