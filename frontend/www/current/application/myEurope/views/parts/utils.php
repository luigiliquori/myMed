<?php 

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tab_bar_default($activeTab) {
	tabs($activeTab, array(
			array("#home", "Partnerships", "retweet"),
			array("#infos", "Informations", "info-sign"),
			array("#blogs", "Blog", "comments"),
			array("#profile", "Profile", "user")
	));
}

function tabs($activeTab, $tabs, $subtitle = APPLICATION_LABEL, $buttonLeft = 1) {
	
	$reverse = true;
	$tabsStr = "";
	foreach ($tabs as $i){
		$tabsStr .=
		'<li><a href="'. $i[0] .'" data-transition="none" data-icon="'. $i[2].'" '.($reverse? 'data-direction="reverse"' : '')
		.($i[0][0]!='#'?'rel="external"':'')
		.($activeTab == $i[0] ? 'class="ui-btn-down-c ui-state-persist"' : '').'>'. _($i[1])
		.'</a></li>';
		if ($i[0] == $activeTab) {
			$reverse = false;
		}
	}
	?>
	<div data-role="header" data-theme="b">
  		<? if ($buttonLeft & 1) { ?>
			<a href="/application/myMed" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="fahome" data-iconpos="notext" data-theme="e">myMed</a>
		<? } else if ($buttonLeft & 2) { ?>
  			<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="signout" data-iconpos="notext">Deconnexion</a>
  		<? } ?>
  		<h1>
  			<a href="./" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?> <span style="font-size: 80%;"> <?= _(APPLICATION_LABEL) ?></span></a>
  		</h1>
  		
  		<? include("social.php"); ?>
  		
  		<div data-role="header" data-theme="d" data-position="fixed" style="display: none;" class="toptab">
			<div data-role="navbar" data-iconpos="bottom" >
				<ul><?= $tabsStr ?></ul>
			</div>
		</div>
  	</div>
	
	<div data-role="footer" data-theme="d" data-position="fixed" style="display: none;" class="iostab">
		<div data-role="navbar" data-iconpos="top" >
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
 	<?
 }
 
 function tabs_simple($title=null, $useback=true, $action=null) {
 	?>
 	<div data-role="header" data-theme="b">
 		<? if ($useback): ?>
 			<a data-rel="back" rel="external" data-icon="arrow-left" style="max-width: 15%;"><?= _("Back") ?></a>
 		<? endif; ?>
  		<h1>
  			<a href="./" rel="external" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?></a>
  			<? if (!is_null($title)): ?>
  				<? foreach($title as $path): ?>
					<?= SEP ?><a href="#<?= $path ?>" style="text-decoration: none; color: white;font-size: 80%;"><?= _($path) ?></a>
				<? endforeach; ?>
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