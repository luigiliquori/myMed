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
				Vous pouvez utiliser les options <strong>Thématiques</strong> et <strong>Programme</strong> pour filtrer votre recherche.</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Rechercher un projet') ?> :</h3>
			
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
			
				<div style="text-align: center;">
					<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
						$('#search_theme').val($('#search_theme_content').val());
						$('#search_other').val($('#search_other_content').val());
					"/>
				</div>
			</div>
			
		</form>
			
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
			<h3><?= _('Notre selection') ?> :</h3>
			<ul data-role="listview" data-filter="true" >
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= _("Title")?> : <?= $item->title ?></h3>
						
						<p style="position: relative; margin-left: 30px;">
							<b><?= _("Themes") ?></b>: <?= Categories::$themes[$item->theme] ?><br/>
							<b><?= _("Programme") ?></b>: <?= Categories::$calls[$item->other] ?><br/><br/>
							<b><?= _('Date of expiration') ?></b>: <?= $item->end ?><br/>
						</p>
						
						<br/>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							reputation: 
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputationMap[$item->getPredicateStr().$item->publisherID] ) { ?>
									<img alt="rep" src="img/yellowStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;" />
								<?php } else { ?>
									<img alt="rep" src="img/grayStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;"/>
								<?php } ?>
							<? } ?>
						</p>
					</a>
				</li>
			<? endforeach ?>
			</ul>
		</div>
			
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
