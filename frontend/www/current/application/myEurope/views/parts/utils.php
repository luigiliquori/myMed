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
<?php 

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tab_bar_default($activeTab) {
	tabs_default(
		$activeTab,
		array(
			array("#home", "Partnerships", "retweet"),
			array("#blogDetails", "Blog", "comments"),
			array("#infos", "Informations", "info-sign"),
			//array("#profile", "Profile", "user")
		),
		3,
		$_SESSION['user']->is_guest===1?'Sign in':(!isset($_SESSION['myEurope'])?'Create your profile':$_SESSION['user']->name)
	);

	include("social.php");
}


 
 function tabs_simple($title=false, $useback="Back") {
 	?>
 	<div data-role="header" data-theme="b">
 		<? if ($useback): ?>
 			<a href="#blog" data-icon="arrow-left" style="max-width: 15%;"><?= _($useback) ?></a>
 		<? endif; ?>
  		<h1>
  			<a href="./" rel="external" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none;<? empty($title)?'color: white;':'' ?>"><?= APPLICATION_NAME ?></a>
  			<? if ($title): ?>
				<?= SEP ?><a style="text-decoration: none; color: white;font-size: 80%;"><?= _($title) ?></a>
			<? endif; ?>
  		</h1>		
  		<? include("social.php"); ?>
  	</div>
  	<?
}

   
/*function about(){
       	?>
<div style="text-align: justify;margin-left:20px;margin-right:20px;">
	<p>
	<?=
		_("<b>MyEurope</b> is a social network which is based on the meta-social network <b><em>myMed</em></b>, available for City Halls, institutions or economic realities (industrial, tourism industry...) of the French South-East areas (PACA, Rhone-Alpes) and the three Italian North-Western Regions (Liguria, Piemonte, Valle d'Aosta), i.e. the areas eligible to the Alcotra Program.")
	?>
	</p>	
	<p>
	<?=
		_("This \"sociapp\" will help the City Hall of the Alps-Mediterranean Euroregion to find partners, among those who joined the social network, in order to create projects together, within European Programs.
	The main targets of <b><em>myMed</em></b> are :
	<ul>
		<li>Help, through the mechanism of myMed's \"matchmaking\", to gather ideas and resources for European project submission or obtain European funds.</li>
		<li>Exchange practices and common cross-border interests in the area of European project creation.</li>
		<li>Inform users about different European calls.</li>
	</ul>")
	?>
	</p>
	
	<p>
	<?=
		_("These information exchanges will be useful to French elected representatives and their Italian counterparts in order to establish a permanent contact between French and Italian people. It will result in a better organization of cross-border activity.")
	?>	
	</p>
</div>

<?
}*/
       
?>