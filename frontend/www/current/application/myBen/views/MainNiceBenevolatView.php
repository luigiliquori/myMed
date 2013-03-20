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
<? include("header.php"); ?>

<div data-role="page" id="login">

	<!--<? include("header-bar.php") ?>-->
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>
	
	<div data-role="content">
	
		<p><?= _("Bonjour <strong>Nice Bénévolat</strong>, que voulez vous faire ?") ?></p>
		
		<a data-role="button" class="mm-left" data-icon="arrow-r" 
		   data-theme="e" data-ajax="false" 
		   href="<?= url("listAnnonces") ?>">
		 	<?= _("Gérer les annonces / candidatures") ?>
		</a>
		 	
		<a data-role="button" class="mm-left" data-icon="arrow-r" 
		   data-theme="e" data-ajax="false"
			href="<?= url("listAssociations") ?>">
			<?= _("Gérer les associations") ?>
		</a>
		
		<a data-role="button" class="mm-left" data-icon="arrow-r" 
		   data-theme="e" data-ajax="false"
			href="<?= url("listBenevoles") ?>">
			<?= _("Gérer les bénévoles") ?>
		</a>
		
	</div>
</div>
	
<?php include("footer.php"); ?>
