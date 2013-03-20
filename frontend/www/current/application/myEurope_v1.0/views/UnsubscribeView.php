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
<? include("header.php"); ?>
<div data-role="page">
	<!-- Header -->
	
	<? tabs_simple('Unsubscribe me'); ?>
	<? include("notifications.php"); ?>
	<div data-role="content" >
	
		<br />
		<? if (!empty($this->res)) :?>
			<h1 style="font-family: Trebuchet MS;"><?= _("Unsubscribed") ?></h1>
		<? else :?>
			<h1 style="font-family: Trebuchet MS;"><?= _("You were not subscribed to these keywords") ?></h1>	
		<? endif ?>
		
	</div>
</div>
<? include("footer.php"); ?>