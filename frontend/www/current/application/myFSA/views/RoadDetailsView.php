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
<div id="roadMap" data-role="page">

	<!-- HEADER BAR-->
	<div data-role="header" data-theme="b" data-position="fixed">
		<h1><?= _("Get directions details")?></h1>
		<a href="?action=localise#searchRoad" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<br>
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c"></ul>
		</div>
		<a id="ceparou06" style="bottom: -40px;right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="<?= MYMED_URL_ROOT ?>system/img/logos/ceparou06.png" style="width:80px;" /></a>
	</div>

</div>