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
$candidat = $candidature->getPublisher();
$profCandidat = ProfileBenevole::getFromUserID($candidature->publisherID);

?>

<div data-role="page">

	<!--<? header_bar(array(
			_("Annonces") => url("listAnnonces"),
			$annonce->titre => url("annonce:details", array("id" => $annonce->id)),
			_("Candidature de " . $candidature->publisherName) => null)) ?>-->
		
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>
	
	<? global $READ_ONLY; $READ_ONLY=true ?>
	<form data-role="content" action="#" >
		
		<div data-role="header" data-theme="b">
			 <h3><?= _("Candidat") ?></h3>
		</div>
		
		<? input("text", "name", "Nom", $candidature->publisherName) ?>
		
		<? input("email", "email", "Email", $candidat->email) ?>
		
		<? input("tel", "tel", "Teléphone", $profCandidat->tel) ?>
		
		<a data-role="button" data-icon="person" data-inline="true" data-ajax="false"
		    href="<?= url("extendedProfile:show", array("id" => $candidature->publisherID)) ?>">
            <?= _("Voir le profil complet") ?>
        </a>
		 
		<div data-role="header" data-theme="b">
			 <h3><?= _("Candidature") ?></h3>
		</div>
		
		<? input("date", "date", "Date", $candidature->begin) ?>
		
		<? input("textarea", "message", "Message", $candidature->message) ?>
		
	</form>
	
</div>
	
<? include("footer.php"); ?>
