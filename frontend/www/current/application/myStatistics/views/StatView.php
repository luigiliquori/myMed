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
<?php

require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");

?>

<!-- Stat temporary View of the statistic application -->
<div id="stat" data-role="page" data-dom-cache="true">
	
	<!-- Header bar  with argument back_button and logout_button-->
	<?php print_header_bar(false, true) ?>
	
	<!--  Includes notification use $this->error="message" $this->success="message" -->
	<?php include('notifications.php'); ?>
	
	<!-- Main part of the page -->
	<div data-role="content" style="text-align: center;">
		This statistic page will be available soon
		
	</div>

	<!-- Footer page with tab bar ?action=stat to highlight the tab -->
	<? print_footer_bar_main("?action=stat"); ?>
	
	<!-- <iframe src="https://www.google.com/analytics/web/?hl=en#report/visitors-overview/a31172274w57882691p59069891/%3Foverview-graphOptions.clearCompareConcept%3Dtrue%26overview-graphOptions.primaryConcept%3Danalytics.visits/"></iframe>-->
	<iframe src="https://www.google.com/analytics/web/?hl=en#report/visitors-overview/a31172274w57882691p59069891/" style="width:100%; height:900px;"></iframe>
	
</div>

<? include_once 'footer.php'; ?>