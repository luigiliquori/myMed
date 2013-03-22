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
	<div data-role="page" id="lang-chooser">
		
		<div data-role="header">
			<h3><?= _("Choisissez la langue") ?></h3>
		</div>
		
		<div data-role="content">
			<ul data-role="listview" >
				<li><a data-ajax="false" href="<?= url("<self>", array("lang" => "fr")); ?>">
					<img src="../../../system/img/flags/fr.png" class="ui-li-icon" >
					Français
				</a></li>
				
				<li><a data-ajax="false" href="<?= url("<self>", array("lang" => "en")); ?>">
					<img src="../../../system/img/flags/en.png" class="ui-li-icon" >
					English	
				</a></li>
				
				<li><a data-ajax="false" href="<?= url("<self>", array("lang" => "it")); ?>">
					<img src="../../../system/img/flags/it.png" class="ui-li-icon" >
					Italiano
				</a></li>
			</ul>
		</div>
		</div>
	</body>
</html>