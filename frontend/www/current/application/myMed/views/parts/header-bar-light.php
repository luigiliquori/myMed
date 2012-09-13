
<?


function tab_bar_main($activeTab) {
	tabs($activeTab, array(
			array("#home", "Applications", "grid"),
			array("#profile", "Profile", "user"),
			array("#store", "Store", "star")
	));
}

function tab_bar_login($activeTab) {
	tabs($activeTab, array(
			array("#about", "A propos", "info"),
			array("#login", "Connexion", "home"),
			array("#register", "Inscription", "grid")
	));
}
   
function tabs($activeTab, $tabs, $useBack = false) {

	$reverse = true;
	?>

	<div class="ui-header ui-bar-e" data-mini="true">
		<span style="color: #588fbe; margin: 9px; font-size: 13px; font-weight: bold;"><a href="."><img alt="myMed" src="../../system/img/logos/mymed"
				style="vertical-align: -25%;" /> </a>Réseau social transfontalier</span>
	</div>
	<div data-role="navbar" data-iconpos="bottom">
		<ul>
			<? if ($useBack): ?>
			<li><a data-rel="back" rel="external" data-icon="back"><?= _("Back") ?> </a></li>
			<? endif ?>
			<? foreach ($tabs as $i): ?>
			<li><a href="<?= $i[0] ?>" data-transition="slide" data-icon="<?= $i[2] ?>" <?= ($reverse) ? 'data-direction="reverse"' : '' ?>
			<?= ($activeTab == $i[0]) ? 'class="ui-btn-down-c ui-state-persist"' : '' ?>> <?= _($i[1]) ?>
			</a>
			</li>
			<? if ($i[0] == $activeTab) {
				$reverse = false;
			}
			endforeach;
			?>
		</ul>
	</div>
	
	<?
}

 
?>
