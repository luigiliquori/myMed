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
<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<?php 

// ---------  dead view -----------

?>

<div data-role="page">
	<? print_header_bar(false, false); ?>
	
	<div data-role="content" style="text-align: center;">
	<br><br>
	<?= _("<b>Congratulations!</b> Your profile has been sent to myEurope team  
			<a href='mailto:myEuropeStaff@gmail.com'>myEuropeStaff@gmail.com</a>		
			for validation") ?>
	<br /><br /><br />
	<a href="/europe" data-role="button" data-inline=true  data-icon="back"><?= APPLICATION_NAME ?></a>
	</div>

</div>

<? include("footer.php"); ?>