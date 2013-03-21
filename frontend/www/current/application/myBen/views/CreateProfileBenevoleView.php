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
<? include("header.php"); ?>

<div data-role="page" id="benevole" >	

	<? tab_bar_main("?action=extendedProfile"); ?>
	<?php  include('notifications.php');?>
	
	<!--<? if ($this->getUserType() == USER_NICE_BENEVOLAT): ?>
		<? header_bar(array(
			_("Bénévoles") => url("listBenevoles"),
			_("Nouveau bénévole") => null)) ?>
	<? else: ?>
		<? header_bar(array(
			_("Création de profil bénévole") => null)) ?>
	<? endif ?>-->
	
	<form data-role="content" method="post" data-ajax="false" id="benForm"
		action="<?= url("extendedProfile:doCreate", array("type" => BENEVOLE)) ?>" >
		
		<p>
			<? if ($this->getUserType() == USER_GUEST) : ?>
			    <?= _("Vous avez déjà un compte bénévole ?") ?>
			    <a href="<?= url("login") ?>">
                    <?= _("connectez vous") ?>
                </a><br/>
			<? endif ?>
		    <?= _("Merci de remplir votre profil.") ?>
		</p>
		
		<? include('ProfileBenevoleForm.php') ?>
		<? wizard_footbar(_("Créer le profil")) ?>
	</form>	
</div>

<? include("footer.php"); ?>
