<?

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tab_bar_main($activeTab, $opts=1) {
	tabs($activeTab, array(
			array("?action=main", "Applications", "tags"),
			array("?action=profile", "Profile", "user"),
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
  			<a href="./" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?> <span style="font-size: 80%;"><?= _(APPLICATION_LABEL) ?></span></a>
  		</h1>
  	</div>
	
	<div data-role="footer" data-theme="d" data-position="fixed" style="display: none;" class="iostab">
		<div data-role="navbar">
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
	<?
}

 
?>
