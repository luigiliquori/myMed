<? include("header.php"); ?>

<?
 function tab_bar($activeTab) {
 	tabs($activeTab, array(
 		array("#infos", "About European programs", "info-sign"),
 		array("#home", "Partenariats", "retweet"),
 		array("#blogs", "Bonnes pratiques", "comments"),
 		array("#profile", $_SESSION['user']->name, "group")
 	), true);
 }

?>


		
<div data-role="page" id="home">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar("#home") ?>
		<? include("notifications.php"); ?>
	</div>
	<div data-role="content" style="text-align:center;">
		
		<br />
		<div data-role="fieldcontain">
			<a href="#search" type="button" class="mymed-huge-button"><?= _('Search a partnership offer') ?></a>
		</div>
		
		<div data-role="fieldcontain">
			<a href="#post" type="button" class="mymed-huge-button"><?= _('Insert a partnership offer') ?></a>
		</div>
			
		<? if ($_SESSION['myEurope']->permission > 1): ?>
		<div data-role="fieldcontain">
			<a href="?action=Admin" type="button"><?= _('Admin') ?></a>
		</div>
		<? endif; ?>
		
		<div id="spacer"></div>
		<div class="logos">
			<img alt="Alcotra" src="../../system/img/logos/fullsize/alcotra" style="width: 100px;"/>
			<img alt="Europe" src="../../system/img/logos/fullsize/EU" style="width: 80px;"/>
			<img alt="myMed" src="../../system/img/logos/mymed" />
		</div>

	</div>
</div>

<div data-role="page" id="profile">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar("#profile") ?>
		<? include("social.php"); ?>
	</div>

	<div data-role="content" style="text-align:center;">
		<?= printMyProfile($_SESSION['myEuropeProfile']) ?>

		<br />
		<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
		<br />
		<a data-role="button" href="?action=logout" rel="external" data-icon="signout" data-inline="true"><?= _('Log Out') ?></a>

		
	</div>
</div>

<div data-role="page" id="blogs">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar("#blogs") ?>
	</div>

	<div data-role="content" style="text-align:center;">
		<br />
		<div data-role="fieldcontain">
			<a href="?action=Blog&blog=Bonnes Pratiques" rel="external" type="button" data-icon="pushpin" class="mymed-huge-button"><?= _('Bonnes Pratiques dans la création de projet et leur soumission ...etc') ?></a>
		</div>
		<div data-role="fieldcontain">
			<a href="?action=Blog&blog=myEurope"  rel="external" type="button"  class="mymed-huge-button"><?= _('Beta Testers Blog') ?></a>
		</div>
		<br />
		<br />
		<br />
		<a href="#createPopup" data-rel="popup" data-inline="true" data-icon="faplus"> <?= _("Create a bew blog") ?></a>
		
		<div data-role="popup" id="createPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<input type="text" id="blogName" placeholder="Blog's name" data-inline="true" />
			<a onclick="$('#createPopup').popup('close');" data-role="button" data-theme="d" data-icon="ok" data-inline="true"><?= _("Create") ?></a>
		</div>
	</div>
</div>

<div data-role="page" id="about">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar("#about") ?>
	</div>
	<div data-role="content">
		
		<br />
		<?= about() ?>
	</div>
</div>

<div data-role="page" id="admin">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar("#admin") ?>
	</div>
	<div data-role="content">
		<br />
		<div style="text-align:center;">
			<span><?= _('Restricted page for admins') ?></span><br />
			<? if ($_SESSION['myEurope']->permission<=1) {?>
				<a data-rel="back" data-icon="back" type="button" data-inline="true" data-theme="e"><?= _('Back') ?></a>
			<? } else { ?>
				<a href="./?action=Admin" type="button" data-inline="true" data-theme="g"><?= _('Access') ?></a>
			<? } ?>
		</div>
	</div>
</div>

<div data-role="page" id="search">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_simple('Recherche de partenaire') ?>
	</div>
	
	<div data-role="content">
		<form action="" id="searchForm" data-ajax="false">
		
			<input type="hidden" name="action" value="Search" />
			<input type="hidden" name="namespace" value="part" />
			
			<input type="hidden" name="t" id="searchedThemes" value="" />
			
			<br />
			
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup">
				<legend><?= _('Offer Themes') ?>:</legend>
				
				<input type="checkbox" id="checkbox-all" />
				<label for="checkbox-all"><?= _('All') ?></label>
				
				<input type="checkbox" data-t="education" id="checkbox-1a"/>
				<label for="checkbox-1a"><?= _("Education, culture and sport") ?></label>

				<input type="checkbox" data-t="travail" id="checkbox-2a"/>
				<label for="checkbox-2a"><?= _("Work and training") ?></label>
				
				<input type="checkbox" data-t="entreprise" id="checkbox-3a"/>
				<label for="checkbox-3a"><?= _("Enterprises, Research and Innovation") ?></label>
				
				<input type="checkbox" data-t="environnement" id="checkbox-4a"/>
				<label for="checkbox-4a"><?= _("Environment, Energies and Risk") ?></label>
				
				<input type="checkbox" data-t="recherche" id="checkbox-7a"/>
				<label for="checkbox-7a"><?= _("Transport, Facilities and Zoning") ?></label>

				<input type="checkbox" data-t="santé" id="checkbox-8a" />
				<label for="checkbox-8a"><?= _("Health and Consumer Protection") ?></label>
				
				<input type="checkbox" data-t="social" id="checkbox-9a" />
				<label for="checkbox-9a"><?= _("Social Affairs") ?></label>
				
				<input type="checkbox" data-t="agriculture" id="checkbox-5a" />
				<label for="checkbox-5a"><?= _("Agriculture") ?></label>

				<input type="checkbox" data-t="peche" id="checkbox-6a" />
				<label for="checkbox-6a"><?= _("Fishing") ?></label>		

				
		    </fieldset>
		    </div>
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup">
				<legend><?= _('Areas') ?>:</legend>

				<div data-role="collapsible-set">
				
				
					<div data-role="collapsible">
						<h3><?= _("France") ?></h3>
						<input type="checkbox" id="checkbox-all3" />
						<label for="checkbox-all3"><?= _('All') ?></label>
						
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
			<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<legend><?= _('Category of searched partners') ?>:</legend>
				<? foreach(Categories::$roles as $i=>$item) :?>
					<input type="checkbox" name="<?= $i ?>" id="checkbox-c<?= $i ?>"/>
					<label for="checkbox-c<?= $i ?>"><?= $item ?></label>
				<? endforeach ?>
		    </fieldset>
		    </div>
		      <div data-role="fieldcontain">
				<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
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
		    <div data-role="fieldcontain">
				<label for="textinputs1"><?= _('keywords') ?>: </label>
				<input id="textinputs1" name="keywords" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
			</div>

			<br />

			<div style="text-align: center;" >
				<input type="submit" class="ui-btn-active ui-state-persist" data-icon="search" data-inline="true" value="<?=_('Search') ?>"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="post">
	
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_simple('Déposer une offre') ?>
	</div>

	<div data-role="content">
		<form action="./" method="post" id="publishForm">
				
			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="namespace" value="part" />
			<?= debug(Categories::$roles[$_SESSION['myEuropeProfile']->role]) ?>
			<input type="hidden" name="cat" value="<?= Categories::$roles[$_SESSION['myEuropeProfile']->role] ?>" />
			
			<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<legend><?= _('Themes') ?>:</legend>
								
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
		    
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup">
				<legend><?= _('Areas') ?>:</legend>
				
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
		    <div data-role="fieldcontain">
				<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
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
		    
			<div data-role="fieldcontain">
				<label for="textinputp1"><?= _('Keywords') ?>: </label>
				<input id="textinputp1" name="keywords" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputp2"><?= _('Date of expiration') ?>: </label>
				<input id="textinputp2" name="date" placeholder="<?= _('date in format year-month-day') ?>" value='' type="date" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputp3"><?= _('Title') ?>: </label>
				<input id="textinputp3" name="title" placeholder="<?= _("partnership or project name") ?>" value='' type="text" />
			</div>
			<textarea id="CLEeditor" id="textContent" name="text"></textarea>

			<div style="text-align: center;" >
				<input type="submit" class="ui-btn-active ui-state-persist"  data-inline="true" data-icon="check" value="<?=_('Insert') ?>" />
			</div>
		</form>
	</div>
</div>


<?php 

function tabs_info(){
	return tabs_simple("Informations", 'info-sign');
}
?>
<? if($_SESSION['user']->lang=="it"): ?>
	<? include("infos_it.php"); ?>
<? else: ?>
	<? include("infos.php"); ?>
<? endif; ?>

<? include("footer.php"); ?>