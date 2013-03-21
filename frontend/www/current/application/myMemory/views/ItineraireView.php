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
<? include("notifications.php")?>

<script type="text/javascript">
$(document).ready(function() {
	goingBack();
	});
</script>
<div id="RoadSheet" data-role="page">

	<div data-role="header" data-position="inline">
		<a href="?action=GoingBack" data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?> </a>
		<h1><?= _("RoadSheet"); ?></h1>
		<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?> </a>
	</div>
	
	<div data-role="content" class="ui-content" role="main">
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c"></ul>
			
		</div>
	</div>
	


	<!-- Footer -->
	<div data-role="footer" data-id="myFooter" data-position="fixed">
		<a id="ceparou06"
				style=""
				href="http://www.ceparou06.fr/"><img alt="ceparou 06"
				src="img/logos/ceparou06.png"
				style="max-height: 35px; max-width: 100px;" /> </a>
	</div>

</div>