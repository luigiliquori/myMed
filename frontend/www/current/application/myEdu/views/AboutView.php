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
<!-- ------------------    -->
<!-- App About View        -->
<!-- ------------------    -->

<div id="aboutView" data-role="page">

 	<? $title = _("Credits"); print_header_bar(true, false, $title); ?>
	
	<div data-role="content" class="content">
		<!-- <a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>-->
		<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		
		<br>
		<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
			<p style='text-align:left'>
				<?= _("<b>MyEdu</b> is an application that has the goal of improving communication between the university and students. <br/>Through myEdu, the university can publish ads on thesis, internships and job opportunities; while students can enjoy all this information through any device with a browser and an Internet connection.<br/>It is also possible to register for one or more topics of interest and receive in its own box email notifications from new content posted on the site.") ?>
			</p>
			<p>
				<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
			</p>
			<p>
				<?= _("If you find an error you can report it at this address: ")?><a href="http://mymed22.sophia.inria.fr/bugreport/index.php" target="_blank"> http://mymed22.sophia.inria.fr/bugreport/index.php </a>
			</p>
			<br/>
			<p>
			<?= _("The myMed consortium is composed by INRIA, Nice Sophia-Antipolis University, Politecnico di Torino, Turin University, and Piémont Oriental University, and it is founded by:")?>
			</p>
			
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
	</div>

</div>
