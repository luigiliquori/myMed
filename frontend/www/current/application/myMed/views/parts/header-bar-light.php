<?

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tab_bar_main($activeTab, $opts=1) {
	tabs($activeTab, array(
			array("?action=main", "Applications", "tags"),
			array("?action=profile", "Profil", "user"),
			array("?action=store", "Store", "shopping-cart")
	), $opts);
}
   
function tabs($activeTab, $tabs, $buttonLeft = false) {

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
	<div data-role="header" data-theme="b" data-position="fixed">
  		<? if ($buttonLeft & 1): ?>
			<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="off" data-iconpos="notext" data-theme="r">Déconnexion</a>
		<? endif ?>
		<? if ($buttonLeft & 2): ?>
			<a data-rel="back" data-icon="arrow-left" style="max-width: 15%;"><?= _("Back") ?></a>
		<? endif ?>
  		<h1>
  			<a href="./" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?> <span style="font-size: 80%;"><?= APPLICATION_LABEL ?></span></a>
  		</h1>
  		<div data-role="header" data-theme="d" style="display: none;" class="toptab">
			<div data-role="navbar" data-role="footer" data-iconpos="bottom" >
				<ul><?= $tabsStr ?></ul>
			</div>
		</div>
		<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
			<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/u/0/101253244628163302593/posts" title="myEurope on Google+"></a>
			<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/myEurope/274577279309326" title="myEurope on Facebook"></a>
			<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/my_europe" title="myEurope on Twitter"></a>
		</span>
  	</div>
	
	<div data-role="footer" data-theme="d" data-position="fixed" style="display: none;" class="iostab">
		<div data-role="navbar" data-role="footer">
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
	<?
}

 
?>
