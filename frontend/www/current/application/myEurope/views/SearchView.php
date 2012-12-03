<div data-role="page" id="search">

	<? print_header_bar(true, "searchHelpPopup"); ?>
	
	<div data-role="content">
	
		<!-- ------------------ -->
		<!-- CONTENT -->
		<!-- ------------------ -->
		<br>
		<form action="" id="searchForm">
			<input type="hidden" name="action" value="Search" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3>Comment rechercher ?</h3>
				<p>La recherche de partenariats passe par la <b>définition</b> du projet: <br />
				Un projet est classé en fonction de son thème ainsi que de sa localisation.
				Il est possible aussi pour affiner la recherche de spécifier le type de partenaire recherché.</p>
				<p>Pour une recherche rapide, saisissez quelques mots-clés séparé par un espace puis lancer la recherche en appuyant sur le bouton "Rechercher"</p>
				<p>Pour une recherche avancée, cliquez sur le bouton "Option" pour utilisé les différents filtres du moteur de recherche</p>
			</div>
			
			<input id="textinput1" name="k[]" placeholder="<?= _('keywords') ?><?= _('separated by a space') ?>" list="keywords" type="search"/>
				
			<div style="text-align: center;" data-role="controlgroup" data-type="horizontal" data-mini="true">
				<a href="#searchOptionPopup" data-rel="popup" data-role="button" data-inline="true" data-icon="gear"><?= _("Option") ?></a>
				<input type="submit" id="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right"/>
			</div>
			
			<div data-role="popup" id="searchOptionPopup" data-theme="b" data-content-theme="d" data-mini="true" class="ui-content">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			
				<h3><?= _('Offer Themes') ?> :</h3>
				<fieldset data-role="controlgroup">
					<? foreach (Categories::$themes as $k=>$v): ?>
						<input type="checkbox" name="t[]" value="<?= $k ?>"
							id="checkbox-<?= $k ?>" /> <label for="checkbox-<?= $k ?>"><?= $v ?>
						</label>
					<? endforeach; ?>
				</fieldset>
				
				<h3><?= _('Areas') ?> : </h3>
				<fieldset data-role="controlgroup">
					<div data-role="collapsible-set" data-mini="true" data-theme="c" data-content-theme="d">
						<div data-role="collapsible" data-collapsed="true">
							<h3>
							<?= _("France") ?>
							</h3>
							<? foreach (Categories::$places_fr as $k=>$v): ?>
							<input type="checkbox" name="pf[]" value="<?= $v ?>"
								id="checkbox-f<?= $k ?>" /> <label for="checkbox-f<?= $k ?>"><?= $v ?>
							</label>
							<? endforeach; ?>
						</div>
						<div data-role="collapsible" data-collapsed="true">
							<h3>
							<?= _("Italy") ?>
							</h3>
							<? foreach (Categories::$places_it as $k=>$v): ?>
							<input type="checkbox" name="pi[]" value="<?= $v ?>"
								id="checkbox-i<?= $k ?>" /> <label for="checkbox-i<?= $k ?>"><?= $v ?>
							</label>
							<? endforeach; ?>
						</div>
						<div data-role="collapsible" data-collapsed="true">
							<h3>
							<?= _("Other") ?>
							</h3>
							<? foreach (Categories::$places_ot as $k=>$v): ?>
							<input type="checkbox" name="po[]" value="<?= $v ?>"
								id="checkbox-o<?= $k ?>" /> <label for="checkbox-o<?= $k ?>"><?= $v ?>
							</label>
							<? endforeach; ?>
						</div>
					</div>
				</fieldset>
				
				<h3><?= _("Programme concerné par l'offre") ?> :</h3>
				<select name="c" id="call" >
				<? foreach (Categories::$calls as $k=>$v): ?>
					<option value="<?= $k ?>">
					<?= $v ?>
					</option>
				<? endforeach; ?>
				</select>
					
				<h3><?= _('Category of searched partners') ?> :</h3>
				<fieldset data-role="controlgroup">
					<? foreach (Categories::$roles as $k=>$v): ?>
					<input type="checkbox" name="r[]" value="<?= $k ?>"
						id="checkbox-<?= $k ?>" /> <label for="checkbox-<?= $k ?>"><?= $v ?>
					</label>
					<? endforeach; ?>
				</fieldset>
			</div>
			
		</form>
		
		<datalist id="keywords">
		<? foreach (Categories::$keywords as $v): ?>
			<option value="<?= _($v) ?>"/>
		<? endforeach; ?>
		</datalist>
		  
	</div>
	
	<!-- ----------------- -->
	<!-- SEARCH HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="searchHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3>Fonctionnement du moteur de recherche:</h3>
		<p>Si vous laissez tous les champs <b>vides</b>, vous
		obtenez toutes les offres publiées à ce jour</p>
		<p>Lorsque vous laissez une categorie <b>vide</b>, elle n'est pas prise en compte dans la recherche.</p>
		<p>Lorsque vous cochez/ remplissez plusieurs champs dans une catégorie, les 
		résultats correspondront à au moins un des critères coché.</p>
	</div>
	
</div>
