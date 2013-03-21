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
<div id="roadMap" data-role="page">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="" data-rel="back" data-icon="arrow-l"/><?= _("Back")?></a>
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
		<a href="?action=main#search" data-transition="none" data-icon="search">Rechercher</a>
	</div>
	
	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px; margin-top: 0px;">
	
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c">
			</ul>
		</div>
		<a id="ceparou06" style="right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="<?= MYMED_URL_ROOT ?>system/img/logos/ceparou06.png" style="width:80px;" /></a>
	</div>
	
</div>