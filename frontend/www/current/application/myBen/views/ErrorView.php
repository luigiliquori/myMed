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
<? include("header.php"); ?>

<div data-role="page" >

	<? require("header-bar.php")?>
	<?php  include('notifications.php');?>
	
	<div data-role="content" >
	
		<a href="#" data-role="button" data-inline="true" data-icon="back" data-theme="e" data-rel="back"><?= _("Retour") ?></a>
	</div>

</div>
	
<? include("footer.php"); ?>