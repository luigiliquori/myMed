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
<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); 
$annonce = $this->annonce;
?>

<div data-role="page" >

	<!--<? header_bar(array(
			_("Annonces") => url("listAnnonces"),
			$annonce->titre => url("annonce:details", array("id" => $annonce->id)),
			_("Edition") => null)) ?>-->
	
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>
	
	<form data-role="content" data-ajax="false" method="post"
		action="<?= url("annonce:doEdit", array("id" => $annonce->id)) ?>"  >
		
		<? require("AnnonceForm.php") ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="<?= _("Enregistrer les modifications") ?>" />
		
	</form>

	
</div>
	
<?php include("footer.php"); ?>
