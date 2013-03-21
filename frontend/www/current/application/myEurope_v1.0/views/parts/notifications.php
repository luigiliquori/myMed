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
<?
/**
 * Display a notification bar for errors or success.
 * Now with close button and fadeOut animation
 */
?>
<div class="ui-bar ui-bar-e ui-header-fixed" onclick="$(this).fadeOut('fast');" id="notification-error" style="width: initial;display:<?= empty($this->error)?'none':'block' ?>">
	<img alt="Warning: " src="/system/img/warning-icon.png" class="ui-li-icon" />
	<h3 style="font-weight:lighter;"><?= $this->error ?></h3>
	<a href="#" style="float: right;" data-role="button" data-icon="delete" data-inline="true" data-iconpos="notext" onclick="$(this).parent().fadeOut('fast');">Button</a>
</div>


<div class="ui-bar ui-bar-e ui-header-fixed" onclick="$(this).fadeOut('fast');" id="notification-success" style="width: initial;display:<?= empty($this->success)?'none':'block' ?>">
	<h3 style="font-weight:lighter;"><?= $this->success ?></h3>
	<a href="#" style="float: right;" data-role="button" data-icon="delete" data-inline="true" data-iconpos="notext" onclick="$(this).parent().fadeOut('fast');">Button</a>
</div>