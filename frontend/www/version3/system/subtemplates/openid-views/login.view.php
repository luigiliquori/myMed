<?php 
/**
 * @param bool $showReqOptFields	true if view have to ask the user witch field to answer
 * @param array<string> $reqFields	list of require fields to answer	(!= null)
 * @param array<string> $optFields	list of optional fields to answer	(!= null)
 */
?>
		<form action="" method="post">
			<div>
				<div>
					<label for="login">Login</label>
					<input id="login" name="login"<?=$loginattribut ?> required="required" />
				</div>
				<div>
					<label for="password">Mot de Passe</label>
					<input id="password" name="password" type="password" required="required" />
				</div>
			</div>
<?php if($showReqOptFields||true/**/):?>
				<fieldset>
					<legend>Données envoyées</legend>
<?php 	foreach($reqFields as $field):?>
					<div>
						<label for="<?=$field?>"><?=$field?></label>
						<input id="<?=$field?>" name="<?=$field?>" type="checkbox" checked="checked" disabled="disabled" />
					</div>
<?php 	endforeach?>
<?php 	foreach($optFields as $field):?>
					<div>
						<label for="<?=$field?>"><?=$field?></label>
						<input id="<?=$field?>" name="<?=$field?>" type="checkbox" checked="checked" />
					</div>
<?php 	endforeach?>
				</fieldset>
<?php endif?>
			<div class="buttons">
				<button type="submit"><span>Connecter</span></button>
				<button type="submit" name="cancel" formnovalidate="formnovalidate">Annuler</button><!-- span forbidden in button : formnovalidate's bug in webkit 534.24 -->
			</div>
		</form>
