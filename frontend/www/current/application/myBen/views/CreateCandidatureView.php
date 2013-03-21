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
<? include("header.php");

// Alias
$candidature = $this->candidature;
$annonce = $candidature->getAnnonce();
?>

<div data-role="page">


	<!--<? header_bar(array(
			_("Annonces") => url("listAnnonces"),
			$annonce->titre => url("annonce:details", array("id" => $annonce->id)),
			_("Candidater") => null)) ?>-->
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>
	
	<div data-theme="e" data-role="header" class="left" >
		<h3><?= sprintf(_("Réponse à l'annonce '%s'"), $annonce->titre) ?></h3>
	</div>
	
	<form data-role="content" action="<?= url("candidature:doCreate") ?>" data-ajax="false" method="post" >
		
		<input type="hidden" name="annonceID" value="<?= $candidature->annonceID ?>" />
		
		<? input("textarea", "message", _("Message"), $candidature->message) ?>
		
		<input type="submit" name="submit" data-role="button" data-theme="g" value="<?= _("Poster la candidature") ?>" />
		
	</form>
	
</div>
	
<? include("footer.php"); ?>