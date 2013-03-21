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

<? require_once("header.php"); ?>
</head>
<body>
	<div data-role="page" id="about" >	
		
		<div data-role="header" data-theme="b" data-position="fixed">
		<a href="?action=main" data-icon="arrow-l" data-ajax="false"><?= _("back")?></a>
		<h1><?= _("About") ?></h1>
	</div>
	

		<div data-role="content" class="content">
			<br>
			<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
			<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
			
			<br />
			<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
				<p><?= _("The aim of the social network <b>myFSA</b> (Fondation Sophia Antipolis), based on the meta-social network <b>myMed</b>, is to provide a solution to promote collaborations, meetings, exchanges and cross-fertilization between Sophia Antipolis stakeholders (academics, companies, entrepreneurs, students, associations) but also with external partners.<br>In addition, myFSA experiments a social network for information exchange:<br>best practices, knowledge, soft transportation assistance between users (workers and firms).<br>It helps the establishment of new entrants in the Science & Technology Park and it takes advantage of geolocation process of its members (not mandatory) to match contacts in terms of distance.<br>The social network also develops a system of e-reputation, owned by MyMed meta-network, that favors convergence and links between the best members (better rated).")?>
				</p>
				<p>
					<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations")?></a>
				</p>
				<br />
				<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
			</div>
		</div>
	</div>
</body>
