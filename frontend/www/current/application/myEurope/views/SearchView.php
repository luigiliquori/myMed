<div data-role="page" id="search">

	<? print_header_bar(true, "searchHelpPopup"); ?>
	
	<div data-role="content">
	
		<!-- ------------------ -->
		<!-- CONTENT -->
		<!-- ------------------ -->
		<br>
		<form action="index.php?action=main" method="POST" data-ajax="false">
			
			<input type="hidden" name="method" value="Search" />
			<input type="hidden" id="search_theme" name="theme" value="" />
			<input type="hidden" id="search_other" name="other" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3>Comment rechercher ?</h3>
				<p>La recherche de partenariats passe par la <b>définition</b> du projet: <br />
				Un projet est classé en fonction de son thème ainsi que de sa localisation.
				Il est possible aussi pour affiner la recherche de spécifier le type de partenaire recherché.</p>
				<p>Pour une recherche rapide, saisissez quelques mots-clés séparé par un espace puis lancer la recherche en appuyant sur le bouton "Rechercher"</p>
				<p>Pour une recherche avancée, cliquez sur le bouton "Option" pour utilisé les différents filtres du moteur de recherche</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Rechercher un projet') ?> :</h3>
				<h3><?= _('keywords') ?> :</h3>
				<div data-role="controlgroup" data-type="horizontal"> 
					<a href="#searchOptionPopup" data-rel="popup" data-role="button" data-inline="true" data-icon="gear" data-mini="true"><?= _("Option") ?></a>
					<a href="#searchExamplePopup" data-rel="popup" data-role="button" data-inline="true" data-icon="star" data-mini="true" data-theme="e" data-iconpos="right"><?= _("Example") ?></a>
				</div>
				<input name="keywords" placeholder="<?= _('separated by a space') ?>" list="keywords" type="search"/>
			</div>
			<div style="text-align: center;">
				<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
					$('#search_theme').val($('#search_theme_content').val());
					$('#search_other').val($('#search_other_content').val());
				"/>
			</div>
			
			<!-- --------------------- -->
			<!-- SEARCH OPTION POPUP -->
			<!-- --------------------- -->
			<div data-role="popup" id="searchOptionPopup" data-theme="c"
				data-content-theme="d" data-mini="true" class="ui-content">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				
				<div data-role="fieldcontain">
	   				<fieldset data-role="controlgroup">
						<select id="search_theme_content" id="call">
						<option value=""><?= _("Themes") ?></option>
						<? foreach (Categories::$themes as $k=>$v): ?>
							<option value="<?= $k ?>">
								<?= $v ?>
							</option>
							<? endforeach; ?>
						</select>
						
						<select id="search_other_content" id="call">
							<option value=""><?= _("Programme") ?></option>
							<? foreach (Categories::$calls as $k=>$v): ?>
								<option value="<?= $k ?>">
									<?= $v ?>
								</option>
							<? endforeach; ?>
						</select>
					</fieldset>
				</div>
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
	
	<!-- --------------------- -->
	<!-- SEARCH EXAMPLE POPUP -->
	<!-- --------------------- -->
	<div data-role="popup" id="searchExamplePopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _('keywords') ?> :</h3>
		<p>Education Alcotra France Emploi Transport Santé culture ...<p>
	</div>
	
</div>
