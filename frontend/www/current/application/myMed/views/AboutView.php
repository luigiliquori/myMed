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
<? require_once("header.php");

function tab_bar_login($activeTab) {
	if(!function_exists('tabs')) {
		function tabs($activeTab, $tabs, $opts = false){
			return tabs_default($activeTab, $tabs, $opts);
		}
	}
	tabs($activeTab, array(
		array("?action=login", "Sign in", "signin"),
		array("?action=register&method=showRegisterView", "Create an account", "th-list"),
		array("#aboutView", "About", "info-sign")
	));
}
?>

<div data-role="page" id="aboutView" >	
	<!-- Page Header -->
	<?php tab_bar_login("#aboutView"); ?>
	   
	<div data-role="content" class="content">
	
		<a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>
		<h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>
		<br>
		<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
			<p style='text-align:left'>
				<?= _("Le projet <b>myMed</b> est né d'une double constatation: l'existence d'un énorme potentiel de développement des activités économiques de la zone transfrontalière, objet de l'action Alcotra, et le manque criant d'infrastructures techniquement avancées en permettant un développement harmonieux. La proposition myMed est née d'une collaboration existante depuis plus de 15 ans entre l'Institut National de Recherche en Informatique et en Automatique (INRIA) de Sophia Antipolis et l'Ecole Polytechnique de Turin, auxquels viennent s'ajouter deux autres partenaires, l'Université de Turin et l'Université du Piémont Oriental.") ?>
			</p>
			<p>
				<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
			</p>
			<br>
		</div>
		<p>
			<?= _("The myMed consortium is composed by INRIA, Nice Sophia-Antipolis University, Politecnico di Torino, Turin University, and Piémont Oriental University, and it is founded by:")?>
		</p>
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>

</div>
<? include_once("footer.php"); ?>