	<div data-role="page" id="lang-chooser">
		
		<div data-role="header">
			<h3><?= _("Choisissez la langue") ?></h3>
		</div>
		
		<div data-role="content">
			<ul data-role="listview" >
				<li><a data-ajax="false" href="<?= url("<self>", array("lang" => "fr")); ?>">
					<img src="../../../system/img/flags/fr.png" class="ui-li-icon" >
					Français
				</a></li>
				
				<li><a data-ajax="false" href="<?= url("<self>", array("lang" => "en")); ?>">
					<img src="../../../system/img/flags/en.png" class="ui-li-icon" >
					English	
				</a></li>
				
				<li><a data-ajax="false" href="<?= url("<self>", array("lang" => "it")); ?>">
					<img src="../../../system/img/flags/it.png" class="ui-li-icon" >
					Italiano
				</a></li>
			</ul>
		</div>
	</body>
</html>