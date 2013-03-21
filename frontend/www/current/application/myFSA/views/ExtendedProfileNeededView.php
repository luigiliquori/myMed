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
</head>
<body>
	<div data-role="page" id="Search" data-theme="b">
		   <div class="wrapper">
			<? include("header-bar.php"); ?>
					
				<div data-role="content">
					<br />
					<p><?= _("To use all options of myFSA please fill your extended profile") ?>:
 						<a href="?action=ExtendedProfile" data-role="button"><?= _("Fill your profile") ?></a>
					</p>
				</div>
			</div>
	</div>
</body>
</html>
