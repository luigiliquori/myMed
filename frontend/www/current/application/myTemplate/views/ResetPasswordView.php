<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>

<? require_once("header.php"); ?>

<div data-role="dialog" data-overlay-theme="b" id="resetpass" >	

	<div data-role="header" data-theme="b">
		<h1>Réinitialisation du mot de passe</h1>
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content">
	
		<!--  Register form -->
		<form action="?action=resetPassword" method="post" data-ajax="false">
				
				<label for="password" >Nouveau mot de passe : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" >Confirmation : </label>
				<input type="password" name="confirm" />
				<br />
				
				<center>
					<input type="submit" data-role="button" data-theme="b" data-inline="true" value="Valider" />
				</center>
		
		</form>
	</div>
	
</div>

<? require_once("footer.php"); ?>
