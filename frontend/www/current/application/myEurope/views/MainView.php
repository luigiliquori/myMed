<? include("header.php"); ?>

<?
function tab_bar_white($activeTab) {
	tabs_white(array(
			//"share" => array(_('Share'), "plus"),
			"about" => array(_("About"), "info"),
			"home" => array(APPLICATION_NAME, "myEurope"),
			"admin" => array(_('Admin'), "gear"),
			"profile" => array(_('Profile'), "profile"),
		),
		$activeTab);
} 
?>
		
<div data-role="page" id="home">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("home") ?>
		<? include("notifications.php"); ?>
	</div>
	<div data-role="content" style="text-align:center;">
		
		<div data-role="fieldcontain" style="padding-top: 5%;padding-bottom: 5%;">
			<a href="#search" id="searchButton" type="button" class="ui-btn-active ui-state-persist" style="width:45%;min-width:250px;" data-inline="true"><?= _('Search a partnership offer') ?></a>
			<a href="#post" id="postButton" type="button" class="ui-btn-active" style="width:45%;min-width:250px;" data-inline="true"><?= _('Insert a partnership offer') ?></a>
		</div>

		<div data-role="fieldcontain" >
			<a href="#infos" style="width:30%;min-width:250px;" class="wrap"
			type="button" data-inline="true" data-theme="d"><?= _('About European programs') ?><span style="font-weight: lighter;"> 2014-2020</span></a>
			<a href="?action=Blog&blog=Alcotra" style="width:30%;min-width:250px;" class="wrap"
			type="button"  data-theme="d" data-inline="true"><?= _('Alcotra Blog') ?><span style="font-weight: lighter;"> 2014-2020</span></a>
			<a href="?action=Blog&blog=myEurope" rel="external" style="width:30%;min-width:250px;" class="wrap"
			type="button" data-theme="d" data-inline="true"><?= _('Beta Testers Blog') ?></a>
		</div>
		
		<div id="spacer">
		</div>
		<div class="social logos">
			<img alt="Alcotra" src="../../system/img/logos/fullsize/alcotra" style="width: 100px;"/>
			<img alt="Europe" src="../../system/img/logos/fullsize/EU" style="width: 80px;"/>
			<img alt="myMed" src="../../system/img/logos/mymed" />
		</div>

	</div>
</div>

<div data-role="page" id="profile">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("profile") ?>
	</div>

	<div data-role="content" style="text-align:center;">
		<?= printMyProfile($_SESSION['myEuropeProfile']) ?>

		<fieldset data-role="controlgroup" style="display:inline-block;width: 50%;">
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-a" value="fr" <?= $_SESSION["user"]->lang == "fr"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _('French') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-b" value="it" <?= $_SESSION["user"]->lang == "it"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _('Italian') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-e" value="en" <?= $_SESSION["user"]->lang == "en"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _('English') ?></label>
		</fieldset>
		<br />
		<fieldset data-role="controlgroup" style="display:inline-block;width: 50%;">
			<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="grid" style="text-align: left;"><?= _('Edit my profile') ?></a>
		</fieldset>
		<fieldset data-role="controlgroup" style="display:inline-block;width: 50%;">
			<a data-role="button" href="?action=logout" rel="external" data-icon="delete" style="text-align: left;"><?= _('Log Out') ?></a>
		</fieldset>
		
	</div>
</div>

<div data-role="page" id="about">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("about") ?>
	</div>
	<div data-role="content">
		<br />
		<div style="text-align: center;">
			<a href="https://plus.google.com/u/0/101253244628163302593/posts" target="_blank" style="padding-left:10px;"><img src="../../system/img/social/googleplus_32" alt="myEurope on Google+"></a>
			<a href="http://www.facebook.com/pages/myEurope/274577279309326" target="_blank" style="padding-left:20px;"><img src="../../system/img/social/facebook_32" alt="myEurope on Facebook"></a>
			<a href="https://twitter.com/my_europe" target="_blank" style="padding-left:20px;"><img src="../../system/img/social/twitter_32" alt="myEurope on Twitter"></a>
		</div>
		<br />
		<?= about() ?>
	</div>
</div>

<div data-role="page" id="admin">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("admin") ?>
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
		<? tabs_2click(
				_('Search a partner'),
				"$('#searchForm').submit();",
				"search") ?>
	</div>
	
	<div data-role="content">
		<form action="./" id="searchForm" data-ajax="false">
		
			<input type="hidden" name="action" value="Search" />
			<input type="hidden" name="namespace" value="part" />
			
			<br />
			
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup" id="themecheckboxes">
				<legend><?= _('Offer Themes') ?>:</legend>
				
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
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup">
				<legend><?= _('Areas') ?>:</legend>

				<div data-role="collapsible-set">
				
				
					<div data-role="collapsible">
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
				<? foreach($this->cats as $i=>$item) :?>
					<input type="checkbox" name="<?= $item ?>" id="checkbox-c<?= $item ?>"/>
					<label for="checkbox-c<?= $item ?>"><?= $i ?></label>
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
		<? tabs_2click(
				_('Insert an offer'),
				"$('#publishForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="./" method="post" id="publishForm">
				
			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="namespace" value="part" />
			<?= debug_r($this->cats[$_SESSION['myEuropeProfile']->role]); ?>
			<input type="hidden" name="cat" value="<?= $_SESSION['myEuropeProfile']->role ?>" />
			
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


<? include("infos.php"); ?>

<? include("footer.php"); ?>