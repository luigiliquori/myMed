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
include("header.php"); ?>

<div data-role="page" id="switch">

	<? include("header-bar.php") ?>
	<? tab_bar_main("?action=extendedProfile"); ?>
	<?php  include('notifications.php');?>
	
	<div data-role="content">

		<p>
			<?= _("Bonjour,") ?><br/>
			<br/>
			<?= _("C'est la première fois que vous venez sur MyBénévolat.") ?><br/>
			<?= _("Merci de renseigner votre profil.") ?><br/>
			<br/>
		    <?= _("Vous êtes ?") ?><br/>
			<br/>
			<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
				href="<?= url('extendedProfile:create', array("type" => BENEVOLE)) ?>">
				<?= _("Un bénévole") ?>
			</a>
			<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
				href="<?= url('extendedProfile:create', array("type" => ASSOCIATION))?>">
                <?= _("Une association") ?>
			</a>		
		</p>
		
	</div>
</div>

<? include("footer.php"); ?>
