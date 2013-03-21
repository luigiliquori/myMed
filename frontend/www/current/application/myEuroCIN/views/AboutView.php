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
<!-- ------------------ -->
<!-- App About View     -->
<!-- ------------------ -->


<!-- Page view -->
<div data-role="page" id="aboutView" >


	<!-- Header bar -->
 	<? $title = _("Credits"); print_header_bar(true, false, $title); ?>

 	
	<!-- Page content -->
	<div data-role="content" class="content">
	
		<!-- <a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>-->
		<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		<!-- <h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>-->
		<br>
		<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
			<p style='text-align:left'>
				<?= _("<b>EURO C.I.N. EEIG</b> (European Economic Interest Grouping), founded in 1994 by the chambers of transborder commerce Cuneo, Imperia and Nice, is now composed of institutions and companies representing the territories of Piedmont, Liguria and Provence-Alpes-Côte d'Azur.<br>In addition to the three founders (Cuneo, Imperia and Nice), the other members are the Chambers of Commerce of Asti, Alessandria, Genoa, Unioncamere Piedmont, Savona Port Authority, Municipality of Cuneo, Cuneo BRE Bank and Spa GEAC.<br>The Group's objectives is the desire to create a comprehensive and common inside and outside of the Euroregion (called Alpes-Maritimes), to promote economic integration, cultural and scientific development and promotion of cross-border flows territorial part of their particularities and traditions.<br>With myEurocin, the Group aims to introduce and encourage visitors most evocative, contemplating nature and well-being, curiosity, historic and artistic and many local products that characterize the Euroregion.<br>MyEurocin content is available to visitors who request their collaboration to improve implementation, providing information and suggestions.<br>Inclusion of new content to the site is possible after authentication.") ?>
			</p>
			<p>
				<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
			</p>
			<p>
				<?= _("If you find an error you can report it at this address: ")?><a href="http://mymed22.sophia.inria.fr/bugreport/index.php" target="_blank"> http://mymed22.sophia.inria.fr/bugreport/index.php </a>
			</p>
			<br/>
			<br/>
			
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>

</div>
