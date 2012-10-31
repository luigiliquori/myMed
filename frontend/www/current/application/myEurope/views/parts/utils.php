<?php 

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tab_bar_default($activeTab) {
	tabs_default(
		$activeTab,
		array(
			array("#home", "Partnerships", "retweet"),
			array("#blogs", "Blog", "comments"),
			array("#infos", "Informations", "info-sign"),
			//array("#profile", "Profile", "user")
		),
		3,
		$_SESSION['user']->name
	);
	include("social.php");
}


 
 function tabs_simple($paths=null, $useback=true, $action=null) {
 	?>
 	<div data-role="header" data-theme="b">
 		<? if ($useback): ?>
 			<a data-rel="back" data-icon="arrow-left" style="max-width: 15%;"><?= _("Back") ?></a>
 		<? endif; ?>
  		<h1>
  			<a href="./" rel="external" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none;"><?= APPLICATION_NAME ?></a>
  			<? if ($paths): ?>
  				<? $title = array_pop($paths); ?>
  				<? foreach($paths as $path): ?>
					<?= SEP ?><a data-rel="back" style="text-decoration: none; font-size: 80%;"><?= _($path) ?></a>
				<? endforeach; ?>
				<?= SEP ?><a style="text-decoration: none; color: white;font-size: 80%;"><?= _($title) ?></a>
			<? endif; ?>
  		</h1>
  		<? if (!is_null($action)): ?>
			<a class="ui-btn-right" href="<?= $action[0] ?>" <?= $action[0][0]!='#'?'rel="external"':'' ?> data-icon="<?= $action[2] ?>"><?= _($actionTitle[1]) ?></a>
		<? endif; ?>
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