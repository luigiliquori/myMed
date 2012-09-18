<? include("header.php"); ?>

<? if(empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>

<div id="home" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content">
		<div  style="padding-top: 5%;">
			<a href="#search" type="button" class="mymed-huge-button">Rechercher un partenariat</a>
		</div>
		
		<div >
			<a href="#post" type="button" class="mymed-huge-button">Déposer une offre de partenariat</a>
		</div>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search"  class="ui-btn-active ui-state-persist">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>

</div>

<div data-role="page" id="search">

	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="#" data-rel="back" data-icon="arrow-l">Retour</a>
		<h1><?= APPLICATION_NAME ?></h1>
		<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-icon="delete" data-theme="r" data-icon="power" class="ui-btn-right" data-iconpos="notext">Déconnexion</a></div>

	<div data-role="content">
	
		<form action="./" id="searchForm" data-ajax="false">
		
			<input type="hidden" name="action" value="Search" />
			<input type="hidden" name="namespace" value="part" />
			
			<br />
			
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
				<div  data-role="collapsible" data-collapsed="false">
					<h3>Thèmes</h3>
				 	<fieldset data-role="controlgroup" id="themecheckboxes">
						<input type="checkbox" id="checkbox-all" />
						<label for="checkbox-all"><?= _('All') ?></label>
						
						<input type="checkbox" name="education" id="checkbox-1a"/>
						<label for="checkbox-1a"><?= _("Education, culture and sport") ?></label>
		
						<input type="checkbox" name="travail" id="checkbox-2a"/>
						<label for="checkbox-2a"><?= _("Work and training") ?></label>
						
						<input type="checkbox" name="entreprise" id="checkbox-3a"/>
						<label for="checkbox-3a"><?= _("Enterprises, Research and Innovation") ?></label>
						
						<input type="checkbox" name="environnement" id="checkbox-4a"/>
						<label for="checkbox-4a"><?= _("Environment, Energies and Risk") ?></label>
						
						<input type="checkbox" name="recherche" id="checkbox-7a"/>
						<label for="checkbox-7a"><?= _("Transport, Facilities and Zoning") ?></label>
		
						<input type="checkbox" name="santé" id="checkbox-8a" />
						<label for="checkbox-8a"><?= _("Health and Consumer Protection") ?></label>
						
						<input type="checkbox" name="social" id="checkbox-9a" />
						<label for="checkbox-9a"><?= _("Social Affairs") ?></label>
						
						<input type="checkbox" name="agriculture" id="checkbox-5a" />
						<label for="checkbox-5a"><?= _("Agriculture") ?></label>
		
						<input type="checkbox" name="peche" id="checkbox-6a" />
						<label for="checkbox-6a"><?= _("Fishing") ?></label>			
		
						
				    </fieldset>
				</div>
			    
				<div  data-role="collapsible" data-collapsed="true">
					<h3><?= _('Areas') ?></h3>
				 	<fieldset data-role="controlgroup">
		
						<div data-role="collapsible-set">
						
							<div data-role="collapsible" data-collapsed="true">
								<h3><?= _("France") ?></h3>
								<input type="checkbox" id="checkbox-all2" />
								<label for="checkbox-all2"><?= _('All') ?></label>
								
								<input type="checkbox" name="Ain" id="checkbox-10b"/>
								<label for="checkbox-10b">Ain</label>
								
								<input type="checkbox" name="Alpes-Maritimes" id="checkbox-1b" />
								<label for="checkbox-1b">Alpes-Maritimes</label>
								
								<input type="checkbox" name="Alpes de Haute-Provence" id="checkbox-5b" />
								<label for="checkbox-5b">Alpes de Haute-Provence</label>
								
								<input type="checkbox" name="Bouches du Rhône" id="checkbox-3b"/>
								<label for="checkbox-3b">Bouches du Rhône</label>
								
								<input type="checkbox" name="Drôme" id="checkbox-12b"/>
								<label for="checkbox-12b">Drôme</label>
								
								<input type="checkbox" name="Hautes-Alpes" id="checkbox-6b" />
								<label for="checkbox-6b">Hautes-Alpes</label>
								
								<input type="checkbox" name="Haute-Savoie" id="checkbox-9b"/>
								<label for="checkbox-9b">Haute-Savoie</label>
								
								<input type="checkbox" name="Isère" id="checkbox-11b"/>
								<label for="checkbox-11b">Isère</label>
								
								<input type="checkbox" name="Rhône" id="checkbox-7b"/>
								<label for="checkbox-7b">Rhône</label>
								
								<input type="checkbox" name="Savoie" id="checkbox-8b" />
								<label for="checkbox-8b">Savoie</label>
								
								<input type="checkbox" name="Var" id="checkbox-2b"/>
								<label for="checkbox-2b">Var</label>
		
								<input type="checkbox" name="Vaucluse" id="checkbox-4b" />
								<label for="checkbox-4b">Vaucluse</label>
		
								
							</div>
							<div data-role="collapsible" data-collapsed="true">
								<h3><?= _("Italy") ?></h3>
								<input type="checkbox" id="checkbox-all2" />
								<label for="checkbox-all2"><?= _('All') ?></label>
								
								<input type="checkbox" name="Alessandria" id="checkbox-21b"/>
								<label for="checkbox-21b">Alessandria</label>
								
								<input type="checkbox" name="Aosta" id="checkbox-13b"/>
								<label for="checkbox-13b">Aosta</label>
								
								<input type="checkbox" name="Asti" id="checkbox-20b"/>
								<label for="checkbox-20b">Asti</label>
								
								<input type="checkbox" name="Biella" id="checkbox-18b"/>
								<label for="checkbox-18b">Biella</label>
								
								<input type="checkbox" name="Cuneo" id="checkbox-14b"/>
								<label for="checkbox-14b">Cuneo</label>
								
								<input type="checkbox" name="Genova" id="checkbox-17b"/>
								<label for="checkbox-17b">Genova</label>
								
								<input type="checkbox" name="Imperia" id="checkbox-16b"/>
								<label for="checkbox-16b">Imperia</label>
								
								<input type="checkbox" name="Savona" id="checkbox-22b"/>
								<label for="checkbox-22b">Savona</label>
								
								<input type="checkbox" name="Torino" id="checkbox-15b"/>
								<label for="checkbox-15b">Torino</label>
								
								<input type="checkbox" name="Vercelli" id="checkbox-19b"/>
								<label for="checkbox-19b">Vercelli</label>
								
							</div>
							<div data-role="collapsible" data-collapsed="true">
								<h3><?= _("Other") ?></h3>
							
								<input type="checkbox" name="Monaco" id="checkbox-23b"/>
								<label for="checkbox-23b">Monaco</label>
								
								<input type="checkbox" name="Suisse" id="checkbox-24b"/>
								<label for="checkbox-24b">Suisse</label>
								
								<input type="checkbox" name="Corse" id="checkbox-25b"/>
								<label for="checkbox-25b">Corse</label>
								
							</div>
						
						</div>
						
				    </fieldset>
			    </div>
			    
				<div  data-role="collapsible" data-collapsed="true">
					<h3><?= _('Category of searched partners') ?></h3>
					<fieldset data-role="controlgroup">
						<? foreach($this->cats as $i=>$item) :?>
							<input type="checkbox" name="<?= $item ?>" id="checkbox-c<?= $item ?>"/>
							<label for="checkbox-c<?= $item ?>"><?= $i ?></label>
						<? endforeach ?>
				    </fieldset>
			    </div>
			    
			    <div  data-role="collapsible" data-collapsed="true">
					<h3><?= _("Programme concerné par l'offre") ?></h3>
					<select name="call" id="call">
						<option value=""><?= _("proposition libre") ?></option>
						<option value="Alcotra">Alcotra</option>
						<option value="Med">Med</option>
						<option value="Alpin"><?= _("Alpin Space") ?></option>
						<option value="IEVP">IEVP CT MED</option>
						<option value="Interreg">Interreg IV C</option>
						<option value="Maritime"><?= _("Italy-France Maritime") ?></option>
					</select>
				</div>
				
			    <div  data-role="collapsible" data-collapsed="true">
					<h3 for="textinputs1"><?= _('keywords') ?> </h3>
					<input id="textinputs1" name="keywords" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
				</div>

			</div>

			<br />

			<div style="text-align: center;" >
				<a href="#result1" data-icon="search" data-inline="true" data-role="button" data-theme="g"><?= _('Rechercher') ?></a>
			</div>
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search"  class="ui-btn-active ui-state-persist">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>
	
</div>

<div data-role="page" id="post">
	
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="#" data-rel="back" data-icon="arrow-l">Retour</a>
		<h1><?= APPLICATION_NAME ?></h1>
		<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-icon="delete" data-theme="r" data-icon="power" class="ui-btn-right" data-iconpos="notext">Déconnexion</a></div>

	<div data-role="content">
	
		<form action="./" method="post" id="publishForm">
			
			<div  data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d">
				<h3>Inserer une nouvelle offre</h3>
					
				<input type="hidden" name="action" value="Publish" />
				<input type="hidden" name="namespace" value="part" />
				<?= debug_r($this->cats[$_SESSION['myEuropeProfile']->role]); ?>
				<input type="hidden" name="cat" value="<?= $_SESSION['myEuropeProfile']->role ?>" />
				
				<div >
					<input id="textinputp3" name="title" placeholder="<?= _("partnership or project name") ?>" value='' type="text" />
				</div>
				
				<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
					<div  data-role="collapsible" data-collapsed="true">
						<h3><?= _('Themes') ?>:</h3>
						<fieldset data-role="controlgroup">
											
							<input type="checkbox" name="education" id="checkbox-1a"/>
							<label for="checkbox-1a"><?= _("Education, culture and sport") ?></label>
			
							<input type="checkbox" name="travail" id="checkbox-2a"/>
							<label for="checkbox-2a"><?= _("Work and training") ?></label>
							
							<input type="checkbox" name="entreprise" id="checkbox-3a"/>
							<label for="checkbox-3a"><?= _("Enterprises, Research and Innovation") ?></label>
							
							<input type="checkbox" name="environnement" id="checkbox-4a"/>
							<label for="checkbox-4a"><?= _("Environment, Energies and Risk") ?></label>
							
							<input type="checkbox" name="recherche" id="checkbox-7a"/>
							<label for="checkbox-7a"><?= _("Transport, Facilities and Zoning") ?></label>
			
							<input type="checkbox" name="santé" id="checkbox-8a" />
							<label for="checkbox-8a"><?= _("Health and Consumer Protection") ?></label>
							
							<input type="checkbox" name="social" id="checkbox-9a" />
							<label for="checkbox-9a"><?= _("Social Affairs") ?></label>
							
							<input type="checkbox" name="agriculture" id="checkbox-5a" />
							<label for="checkbox-5a"><?= _("Agriculture") ?></label>
			
							<input type="checkbox" name="peche" id="checkbox-6a" />
							<label for="checkbox-6a"><?= _("Fishing") ?></label>	
							
					    </fieldset>
				    </div>
			    
					<div  data-role="collapsible" data-collapsed="true">
						<h3><?= _('Areas') ?>:</h3>
					 	<fieldset data-role="controlgroup">
							
							<div data-role="collapsible-set">
							
							
								<div data-role="collapsible">
									<h3><?= _("France") ?></h3>
									
									<input type="checkbox" name="Ain" id="checkbox-10b"/>
									<label for="checkbox-10b">Ain</label>
									
									<input type="checkbox" name="Alpes-Maritimes" id="checkbox-1b" />
									<label for="checkbox-1b">Alpes-Maritimes</label>
									
									<input type="checkbox" name="Alpes de Haute-Provence" id="checkbox-5b" />
									<label for="checkbox-5b">Alpes de Haute-Provence</label>
									
									<input type="checkbox" name="Bouches du Rhône" id="checkbox-3b"/>
									<label for="checkbox-3b">Bouches du Rhône</label>
									
									<input type="checkbox" name="Drôme" id="checkbox-12b"/>
									<label for="checkbox-12b">Drôme</label>
									
									<input type="checkbox" name="Hautes-Alpes" id="checkbox-6b" />
									<label for="checkbox-6b">Hautes-Alpes</label>
									
									<input type="checkbox" name="Haute-Savoie" id="checkbox-9b"/>
									<label for="checkbox-9b">Haute-Savoie</label>
									
									<input type="checkbox" name="Isère" id="checkbox-11b"/>
									<label for="checkbox-11b">Isère</label>
									
									<input type="checkbox" name="Rhône" id="checkbox-7b"/>
									<label for="checkbox-7b">Rhône</label>
									
									<input type="checkbox" name="Savoie" id="checkbox-8b" />
									<label for="checkbox-8b">Savoie</label>
									
									<input type="checkbox" name="Var" id="checkbox-2b"/>
									<label for="checkbox-2b">Var</label>
			
									<input type="checkbox" name="Vaucluse" id="checkbox-4b" />
									<label for="checkbox-4b">Vaucluse</label>
			
									
								</div>
								<div data-role="collapsible">
									<h3><?= _("Italy") ?></h3>
									
									<input type="checkbox" name="Alessandria" id="checkbox-21b"/>
									<label for="checkbox-21b">Alessandria</label>
									
									<input type="checkbox" name="Aosta" id="checkbox-13b"/>
									<label for="checkbox-13b">Aosta</label>
									
									<input type="checkbox" name="Asti" id="checkbox-20b"/>
									<label for="checkbox-20b">Asti</label>
									
									<input type="checkbox" name="Biella" id="checkbox-18b"/>
									<label for="checkbox-18b">Biella</label>
									
									<input type="checkbox" name="Cuneo" id="checkbox-14b"/>
									<label for="checkbox-14b">Cuneo</label>
									
									<input type="checkbox" name="Genova" id="checkbox-17b"/>
									<label for="checkbox-17b">Genova</label>
									
									<input type="checkbox" name="Imperia" id="checkbox-16b"/>
									<label for="checkbox-16b">Imperia</label>
									
									<input type="checkbox" name="Savona" id="checkbox-22b"/>
									<label for="checkbox-22b">Savona</label>
									
									<input type="checkbox" name="Torino" id="checkbox-15b"/>
									<label for="checkbox-15b">Torino</label>
									
									<input type="checkbox" name="Vercelli" id="checkbox-19b"/>
									<label for="checkbox-19b">Vercelli</label>
									
								</div>
								<div data-role="collapsible">
									<h3><?= _("Other") ?></h3>
								
									<input type="checkbox" name="Monaco" id="checkbox-23b"/>
									<label for="checkbox-23b">Monaco</label>
									
									<input type="checkbox" name="Suisse" id="checkbox-24b"/>
									<label for="checkbox-24b">Suisse</label>
									
									<input type="checkbox" name="Corse" id="checkbox-25b"/>
									<label for="checkbox-25b">Corse</label>
								</div>
							
							
							</div>
					    </fieldset>
				    </div>
				    
				 	<div  data-role="collapsible" data-collapsed="true">
						<h3><?= _("Programme concerné par l'offre") ?>:</h3>
						<select name="call" id="call">
							<option value=""><?= _("proposition libre") ?></option>
							<option value="Alcotra">Alcotra</option>
							<option value="Med">Med</option>
							<option value="Alpin"><?= _("Alpin Space") ?></option>
							<option value="IEVP">IEVP CT MED</option>
							<option value="Interreg">Interreg IV C</option>
							<option value="Maritime"><?= _("Italy-France Maritime") ?></option>
						</select>
			    
						<div >
							<label for="textinputp1"><?= _('Keywords') ?>: </label>
							<input id="textinputp1" name="keywords" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
						</div>
						<div >
							<label for="textinputp2"><?= _('Date of expiration') ?>: </label>
							<input id="textinputp2" name="date" placeholder="<?= _('date in format year-month-day') ?>" value='' type="date" />
						</div>
					</div>
				
				</div>
				
			</div>
				
			<textarea id="CLEeditor" name="text"></textarea>

			<div style="text-align: center;" >
				<a href="#post1" data-role="button" data-inline="true" data-icon="check" data-theme="g" data-rel="dialog"><?=_('Insert') ?></a>
			</div>
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search"  class="ui-btn-active ui-state-persist">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>
	
</div>

<!-- ********************************************************************************* -->

<div data-role="page" id="result1">
	
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="#" data-rel="back" data-icon="arrow-l">Retour</a>
		<h1><?= APPLICATION_NAME ?></h1>
	<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-icon="delete" data-theme="r" data-icon="power" class="ui-btn-right" data-iconpos="notext">Déconnexion</a></div>

	<div data-role="content">
		<div style="margin-bottom: 16px;">
			<fieldset id="radio-group1" data-role="controlgroup" data-mini="true" data-type="horizontal" style="display:inline-block;">
				<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-t" value="title" checked='checked'/>
				<label for="radio-view-t"><?= _("title") ?></label>
				<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-a"value="partner"/>
				<label for="radio-view-a"><?= _("partner") ?></label>
				<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-b" value="date" />
				<label for="radio-view-b"><?= _("date") ?></label>
				<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-e" value="reputation" />
				<label for="radio-view-e"><?= _("reputation") ?></label>
			</fieldset>
			
			<br /><br />
			
			<ul id="matchinglist" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?= _("filter") ?>" style="clear:both;">
	
				<? if (count($this->result) == 0) :?>
				<li>
					<h4><?= _("Aucune offre trouvée...") ?></h4>
				</li>
				<? endif ?>
	
				<? foreach($this->result as $item) : ?>
				
				<li data-id="<?= prettyprintUser($item->user) ?>" data-partner="<?= $item->partner ?>" data-time="<?= $item->time ?>" data-title="<?= $item->title ?>">
				<a href="?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>"><span
						class="ui-link"><?= $item->title ?> </span> &ndash; <span style="font-weight: lighter;"><?= prettyprintUser($item->user) ?>  (<?= date('j/n/y G:i', $item->time) ?>)</span>
					</a>
				</li>
				<? endforeach ?>
	
				<? if (!empty($this->suggestions)) :?>
				<li data-role="list-divider">Suggestions:</li>
				<? foreach($this->suggestions as $item) : ?>
				<li><a href="./?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>"> <b>...</b> : <?= print_r($item) ?><br />
				</a>
				</li>
				<? endforeach ?>
				<? endif ?>
	
			</ul>
			
			<div style="text-align: center;" >
				<a id="subscribeButton" type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="alert"
				onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:part", "<?= APPLICATION_NAME.":".$_GET['namespace'] ?>", <?= json_encode($this->index) ?>); $(this).addClass("ui-disabled");'><?= _("Recevoir des offres ultérieurement") ?></a>
			</div>
		</div>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search"  class="ui-btn-active ui-state-persist">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>
	
</div>

<!-- ********************************************************************************* -->

<div data-role="page" id="post1">
	
	<div data-role="header" data-theme="b">
		<h1><?= APPLICATION_NAME ?></h1>
	</div>
	
	<div data-role="content">
		<br />
		<?= _("Votre offre à bien été prise en compte") ?> !
		 <br /><br />
		 <div style="text-align: center;" >
			 <a href="" rel="external" type="button" data-inline="true"> <?= _("Voir les offres simillaires") ?> </a>
		 </div>
		 <br />
	</div>
	
</div>

<!-- ********************************************************************************* -->


<? include("infos.php"); ?>
<? include("InfoView.php"); ?>
<? include("ProfileView.php"); ?>
<? include("UpdateProfileView.php"); ?>
<? include("StoreView.php"); ?>

<? include("footer.php"); ?>