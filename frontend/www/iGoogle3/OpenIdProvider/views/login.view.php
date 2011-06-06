<?php 
/**
 * @param bool $showReqOptFields	true if view have to ask the user witch field to answer
 */
?>
		<form action="" method="post">
			<div>
				<div>
					<label for="login">Login&nbsp;:</label>
					<input id="login" name="login"<?=$loginattribut ?> />
				</div>
				<div>
					<label for="password">Mot de Passe&nbsp;:</label>
					<input id="password" name="password" type="password" />
				</div>
<?php if($showReqOptFields):?>
<?php 
				echo 'todo : gérer les champs "required" et "optional"';
?>
<?php endif?>
			</div>
			<button type="submit" name="cancel"><span>Annuler</span></button>
			<button type="submit"><span>Connecter</span></button>
		</form>