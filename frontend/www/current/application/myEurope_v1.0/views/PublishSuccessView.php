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

	<? tabs_simple('Published', false); ?>
	<? include("notifications.php"); ?>
	<div data-role="content" style="text-align: center;">
		<span style="height: 50px;"></span>
		<br />
		<?= _("Your partnership offer has been successfully published") ?>
		 <br /><br />
		
		<a href="?action=search&<?= $this->req ?>" type="button" data-inline="true"> <?= _("See similar offers") ?> </a><br />
		
	</div>
</div>

<? include("footer.php"); ?>