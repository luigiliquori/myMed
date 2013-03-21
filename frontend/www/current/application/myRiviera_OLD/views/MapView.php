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

<div id="Map" data-role="page" class="page-map">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="?action=main" data-icon="arrow-l" data-ajax="false"/><?= _("Main menu")?></a>
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
		<a href="?action=main#search" data-transition="none" data-icon="search">Rechercher</a>
	</div>

	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px; margin-top: 0px;">
		
		<!-- MAP -->
		<div id="myRivieraMap"></div>
		
		<div id="steps" data-role="controlgroup" data-type="horizontal" >
			<a id="prev-step" data-role="button" data-icon="arrow-l" title="précédent"></a>
			<a href="#roadMap" data-role="button">Détails</a>
			<a id="next-step" data-role="button" data-icon="arrow-r" data-iconpos="right" title="suivant"></a>
		</div>

	</div>


</div>