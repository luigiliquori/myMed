<?
function tab_bar_main($activeTab) {
	tabs($activeTab, array(
			array("#home", "Applications", "tags"),
			array("#profile", "Profile", "user"),
			array("#store", "Store", "shopping-cart")
	), true);
}

function tab_bar_login($activeTab) {
	tabs($activeTab, array(
			array("#about", "A propos", "info-sign"),
			array("#login", "Connexion", "signin"),
			array("#register", "Inscription", "th-list")
	));
}
   
function tabs($activeTab, $tabs, $useLogOut = false) {

	$reverse = true;
	$tabsStr = "";
	foreach ($tabs as $i){
		$tabsStr .=
		'<li><a href="'. $i[0] .'" data-transition="slide" data-icon="'. $i[2].'" '.($reverse? 'data-direction="reverse"' : '')
		.($activeTab == $i[0] ? 'class="ui-btn-down-c ui-state-persist"' : '').'>'. _($i[1])
		.'</a></li>';
		if ($i[0] == $activeTab) {
			$reverse = false;
		}
	}
	?>
	<div data-role="header" data-theme="b" data-position="fixed" style="height: 34px;">
  		<? if ($useLogOut): ?>
			<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="off" data-iconpos="notext" data-theme="r">Déconnexion</a>
		<? endif ?>
  		<h1>
  			<a href="./" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?><span class="largeWidth">Réseau social transfontalier</span></a>
  		</h1>
  	</div>
	
	<div data-role="header" data-theme="d" data-position="fixed" style="top: 34px;" class="toptab">
		<div data-role="navbar" data-role="footer" data-iconpos="bottom" >
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
	<div data-role="footer" data-theme="d" data-position="fixed" style="display: none;" class="iostab">
		<div data-role="navbar" data-role="footer" data-iconpos="bottom" >
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
	
	
	<?
}

 
?>
