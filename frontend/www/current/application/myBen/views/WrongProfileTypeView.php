<!--
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
 -->
<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); 
$annonce = $this->annonce;
?>

<div data-role="page" >

	<? require("header-bar.php")?>
	<div data-role="content" >
	
		<div data-theme="e" data-role="header" >
			<h3><?= _("Mauvais type the compt") ?>e</h3>
		</div>
	
		<p>
			<?= _("Vous êtes loggué avec un compte") ?> "<?= $this->actualProfileType ?>",
		    <?= _("mais cette action nécéssite un compte") ?> "<?= $this->profileType ?>".
			<br/>
			<?= _("Merci de vous logguer avec un autre compte ou de créer un nouveau profil") ?>e :
			
		</p>
		
		<a data-role="button" data-icon="delete" data-theme="b"
				href="<?= url("logout") ?>"><?= _("Se délogguer") ?></a>
	</div>
	
	
</div>
	
<?php include("footer.php"); ?>
