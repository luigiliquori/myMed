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

<? include("header.php") ?>
<div data-role="page" >

	<? header_bar() ?>

	<div data-role="content" >

		<ul data-role="listview" data-inset="true">
			<li><a href="<?= url("institutionCategory") ?>">
				<img src="img/institution.png">
				<?= _("Institution")?>
			</a></li>
			<li><a href="<?= url() ?>">
			
			</a></li>
		</ul>
		
	</div>

</div>
<? include("footer.php") ?>