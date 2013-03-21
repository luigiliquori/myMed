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
	<div data-role="page" data-theme="a">
		<div data-role="content" data-theme="c" class="ui-overlay-shadow ui-corner-bottom ui-content ui-body-c" role="main">
			<h1><?= _("MyMemory_StopNeedHelp"); ?></h1>
			<p><?= _("MyMemory_askStopNeedHelp"); ?></p>
			<a href="?action=StopEmergency&confirm=ok" data-role="button" data-theme="r" data-ajax="false"><?= _("MyMemory_confimStopNeedHelp"); ?></a>
			<a href="?action=NeedHelp" data-role="button" data-rel="back" data-theme="a"><?= _("MyMemory_cancelStopNeedHelp"); ?></a>
		</div>
	</div>
