<? include("header.php"); ?>

<?
function tab_bar_white($activeTab) {
	tabs_white(array(
			//"share" => array(_('Share'), "plus"),
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
		<h3 class="ui-link"><?= _('Partnership') ?>:</h3>
		
		<a href="#search" type="button" class="ui-btn-active ui-state-persist" style="width:45%;min-width:240px;" data-inline="true"><?= _('Search a partner') ?></a>
		<a href="#post" type="button" class="ui-btn-active ui-state-persist" style="width:45%;min-width:240px;" data-inline="true"><?= _('Insert an offer') ?></a>
		
		
		<h3 class="ui-link"><?= _('Infomations') ?>:</h3>
		
			<a href="#infos" style="width:30%;min-width:240px;" class="wrap"
			type="button" data-inline="true" data-theme="d"><?= _('About European programs') ?><span style="font-weight: lighter;"> 2014-2020</span></a>
			<a href="?action=Blog&blog=alcotra" style="width:30%;min-width:240px;" class="wrap"
			type="button"  data-theme="d" data-inline="true"><?= _('Alcotra Blog') ?><span style="font-weight: lighter;"> 2014-2020</span></a>
			<a href="?action=Blog&blog=myEurope" style="width:30%;min-width:240px;" class="wrap"
			type="button" data-theme="d" data-inline="true"><?= _('Beta Testers Blog') ?></a>
		
		<div data-role="collapsible" data-mini="true" data-inline="true">
			<h3 style="margin:auto;width:136px;"><?= _("About") ?></h3>
			<?= about(); ?>
		</div>
		<div class="myLogos">
			<img alt="Alcotra" src="../../system/img/logos/fullsize/alcotra" />
			<img alt="Europe" src="../../system/img/logos/fullsize/EU" />
			<img alt="myMed" src="../../system/img/logos/mymed" />
		</div>
		
		<div style="opacity:0.7;margin-top:100px;">
			<span class='st_googleplus_large' displayText='Google +'></span>
			<span class='st_facebook_large' displayText='Facebook'></span>
			<span class='st_twitter_large' displayText='Tweet'></span>
		</div>
		
		
		
		
	</div>
</div>

<div data-role="page" id="profile">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("profile") ?>
	</div>

	<div data-role="content">

		
		<br />
		<?= printMyProfile($_SESSION['myEuropeProfile']) ?>
		<div style="text-align:center;">
		<a type="button" href="?action=ExtendedProfile&edit=false" data-inline="true" data-theme="d" data-icon="grid"><?= _('Edit my profile') ?></a>
		<br />
		<br />
		<fieldset data-role="controlgroup" data-type="horizontal" style="display:inline-block;vertical-align: middle;">
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-a" value="fr" <?= $_SESSION["user"]->lang == "fr"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _('French') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-b" value="it" <?= $_SESSION["user"]->lang == "it"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _('Italian') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-e" value="en" <?= $_SESSION["user"]->lang == "en"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _('English') ?></label>
		</fieldset>
		<br />
		<br />
		<br />
		<a data-role="button" href="?action=logout" rel="external" class="ui-btn-active" data-mini="true" data-inline="true"><?= _('Log Out') ?></a>
		</div>
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
			<? if ($_SESSION['myEuropeProfile']->permission<=1) {?>
				<a href="#home" type="button" data-inline="true" data-theme="e" data-icon="back"><?= _('Back') ?></a>
			<? } else { ?>
				<a href="./?action=Admin" type="button" data-inline="true" data-theme="g"><?= _('Access') ?></a>
			<? } ?>
		</div>
	</div>
</div>

<div data-role="page" id="share">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("share") ?>
	</div>
	<div data-role="content" style="text-align:center;">
	
			<br /><br />
			<span><?= _("Share") ?> <?= APPLICATION_NAME ?>:</span>
			<div id='shareThisShareEgg' class='shareEgg' style="display: inline-block;vertical-align: middle;"></div>
		</div>
	</div>
</div>

<div data-role="page" id="rep">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("rep") ?>
	</div>
	<div data-role="content">


		<fieldset data-role="controlgroup" id="themecheckboxes">
			<legend><b>Selectionnez parmi les offres de partenaires vus:</b></legend>
	

			<input type="checkbox" name="pr1" id="checkbox-5a" />
			<label for="checkbox-5a">Offre1 <a href="">100%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a></label>
			
			<input type="checkbox" name="pr2" id="checkbox-6a" />
			<label for="checkbox-6a">Offre4 <a href="">90%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a></label>
			
			<input type="checkbox" name="pr3" id="checkbox-7a"/>
			<label for="checkbox-7a">Offre3 <a href="">80%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a><span></label>
	
			<input type="checkbox" name="pr4" id="checkbox-8a" />
			<label for="checkbox-8a">Offre2 <a href="">75%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a></label>
		</fieldset>
		
	    <a type="button" data-mini="true" data-inline="true" id="clearRepButton">Effacer</a><br /><br />
        <b>Notez-les:</b>&nbsp;&nbsp;
        
        <a data-role="button" data-iconpos="top" data-mini="true" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"
			id="rep0">0</a>
		<a data-role="button" data-iconpos="top" data-mini="true" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"
			id="rep25">25</a>
		<a data-role="button" data-iconpos="top" data-mini="true" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"
			id="rep50">50</a>
		<a data-role="button" data-iconpos="top" data-mini="true" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"
			id="rep75">75</a>
		<a data-role="button" data-iconpos="top" data-mini="true" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"
			id="rep100">100</a>
			
		<br /><br />
		<a type="button" data-mini="true" data-inline="true" href="?action=search&namespace=part">Voir les offres les mieux notées</a><br /><br />

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
		<form action="./" id="searchForm">
		
			<input type="hidden" name="action" value="Search" />
			<input type="hidden" name="namespace" value="part" />
			
			<br />
			<label for="tags"><?= _("Keywords") ?></label>
			<input type="search" name="themes" id="tags" placeholder="mots clés (séparés par un espace ou +)" value="" />
	
		 	<fieldset data-role="controlgroup" id="themecheckboxes">
				<legend><b><?= _('Themes') ?>:</b></legend>
				
				<input type="checkbox" id="checkbox-all" />
				<label for="checkbox-all"><?= _('All') ?></label>
				
				<input type="checkbox" name="education" id="checkbox-1a"/>
				<label for="checkbox-1a"><?= _("Education, culture & sport") ?></label>

				<input type="checkbox" name="social" id="checkbox-2a"/>
				<label for="checkbox-2a"><?= _("Work") ?></label>
				
				<input type="checkbox" name="entreprise" id="checkbox-3a"/>
				<label for="checkbox-3a"><?= _("Enterprises, Innovation") ?></label>

				<input type="checkbox" name="environnement" id="checkbox-4a"/>
				<label for="checkbox-4a"><?= _("Environment, energy & transport") ?></label>
				
				<input type="checkbox" name="agriculture" id="checkbox-5a" />
				<label for="checkbox-5a"><?= _("Agriculture") ?></label>

				<input type="checkbox" name="peche" id="checkbox-6a" />
				<label for="checkbox-6a"><?= _("Fishing") ?></label>
				
				<input type="checkbox" name="transfontalier" id="checkbox-7a"/>
				<label for="checkbox-7a"><?= _("Research") ?></label>

				<input type="checkbox" name="recherche" id="checkbox-8a" />
				<label for="checkbox-8a"><?= _("Health and Consumer Protection") ?></label>

				
		    </fieldset>

		 	<fieldset data-role="controlgroup">
				<legend><b><?= _('Areas') ?>:</b></legend>
				
				<input type="checkbox" id="checkbox-all2" />
				<label for="checkbox-all2"><?= _('All') ?></label>
				
				<div data-role="collapsible-set">
				
				
					<div data-role="collapsible">
						<h3><?= _("France") ?></h3>
						<input type="checkbox" name="Alpes-Maritimes" id="checkbox-1b" />
						<label for="checkbox-1b">Alpes-Maritimes</label>
						
						<input type="checkbox" name="Var" id="checkbox-2b"/>
						<label for="checkbox-2b">Var</label>
						
						<input type="checkbox" name="Bouches du Rhône" id="checkbox-3b"/>
						<label for="checkbox-3b">Bouches du Rhône</label>
						
						<input type="checkbox" name="Vaucluse" id="checkbox-4b" />
						<label for="checkbox-4b">Vaucluse</label>
						
						<input type="checkbox" name="Alpes de Haute-Provence" id="checkbox-5b" />
						<label for="checkbox-5b">Alpes de Haute-Provence</label>
						
						<input type="checkbox" name="Hautes-Alpes" id="checkbox-6b" />
						<label for="checkbox-6b">Hautes-Alpes</label>
						
						<input type="checkbox" name="Rhône" id="checkbox-7b"/>
						<label for="checkbox-7b">Rhône</label>
						
						<input type="checkbox" name="Savoie" id="checkbox-8b" />
						<label for="checkbox-8b">Savoie</label>
						
						<input type="checkbox" name="Haute-Savoie" id="checkbox-9b"/>
						<label for="checkbox-9b">Haute-Savoie</label>
						
						<input type="checkbox" name="Ain" id="checkbox-10b"/>
						<label for="checkbox-10b">Ain</label>
						
						<input type="checkbox" name="Isère" id="checkbox-11b"/>
						<label for="checkbox-11b">Isère</label>
						
						<input type="checkbox" name="Drôme" id="checkbox-12b"/>
						<label for="checkbox-12b">Drôme</label>
					</div>
					<div data-role="collapsible">
						<h3><?= _("Italy") ?></h3>
						<input type="checkbox" name="Aosta" id="checkbox-13b"/>
						<label for="checkbox-13b">Aosta</label>
						
						<input type="checkbox" name="Cuneo" id="checkbox-14b"/>
						<label for="checkbox-14b">Cuneo</label>
						
						<input type="checkbox" name="Torino" id="checkbox-15b"/>
						<label for="checkbox-15b">Torino</label>
						
						<input type="checkbox" name="Imperia" id="checkbox-16b"/>
						<label for="checkbox-16b">Imperia</label>
						
						<input type="checkbox" name="Genova" id="checkbox-17b"/>
						<label for="checkbox-17b">Genova</label>
						
						<input type="checkbox" name="Biella" id="checkbox-18b"/>
						<label for="checkbox-18b">Biella</label>
						
						<input type="checkbox" name="Vercelli" id="checkbox-19b"/>
						<label for="checkbox-19b">Vercelli</label>
						
						<input type="checkbox" name="Asti" id="checkbox-20b"/>
						<label for="checkbox-20b">Asti</label>
						
						<input type="checkbox" name="Alessandria" id="checkbox-21b"/>
						<label for="checkbox-21b">Alessandria</label>
						
						<input type="checkbox" name="Savona" id="checkbox-22b"/>
						<label for="checkbox-22b">Savona</label>
					
					</div>
					<div data-role="collapsible">
						<h3><?= _("Other") ?></h3>
					
						<input type="checkbox" name="Monaco" id="checkbox-23b"/>
						<label for="checkbox-23b">Monaco</label>
						
						<input type="checkbox" name="Suisse" id="checkbox-24b"/>
						<label for="checkbox-24b">Suisse</label>
					</div>
				
				
				
				
				
				
				</div>
				
		    </fieldset>

			<br />

			<div style="text-align: center;" >
				<input type="submit" data-theme="b" data-icon="search" data-inline="true" value="Chercher"/>
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
			

			<fieldset data-role="controlgroup">
				<legend><b><?= _('Themes') ?>:</b></legend>
								
				<input type="checkbox" name="education" id="checkbox-1a"/>
				<label for="checkbox-1a"><?= _("Education, culture & sport") ?></label>

				<input type="checkbox" name="social" id="checkbox-2a"/>
				<label for="checkbox-2a"><?= _("Work") ?></label>
				
				<input type="checkbox" name="entreprise" id="checkbox-3a"/>
				<label for="checkbox-3a"><?= _("Enterprises, Innovation") ?></label>

				<input type="checkbox" name="environnement" id="checkbox-4a"/>
				<label for="checkbox-4a"><?= _("Environment, energy & transport") ?></label>
				
				<input type="checkbox" name="agriculture" id="checkbox-5a" />
				<label for="checkbox-5a"><?= _("Agriculture") ?></label>

				<input type="checkbox" name="peche" id="checkbox-6a" />
				<label for="checkbox-6a"><?= _("Fishing") ?></label>
				
				<input type="checkbox" name="transfontalier" id="checkbox-7a"/>
				<label for="checkbox-7a"><?= _("Research") ?></label>

				<input type="checkbox" name="recherche" id="checkbox-8a" />
				<label for="checkbox-8a"><?= _("Health and Consumer Protection") ?></label>
				
		    </fieldset>

		 	<fieldset data-role="controlgroup">
				<legend><b><?= _('Areas') ?>:</b></legend>
				
				<div data-role="collapsible-set">
				
				
					<div data-role="collapsible">
						<h3><?= _("France") ?></h3>
						<input type="checkbox" name="Alpes-Maritimes" id="checkbox-1b" />
						<label for="checkbox-1b">Alpes-Maritimes</label>
						
						<input type="checkbox" name="Var" id="checkbox-2b"/>
						<label for="checkbox-2b">Var</label>
						
						<input type="checkbox" name="Bouches du Rhône" id="checkbox-3b"/>
						<label for="checkbox-3b">Bouches du Rhône</label>
						
						<input type="checkbox" name="Vaucluse" id="checkbox-4b" />
						<label for="checkbox-4b">Vaucluse</label>
						
						<input type="checkbox" name="Alpes de Haute-Provence" id="checkbox-5b" />
						<label for="checkbox-5b">Alpes de Haute-Provence</label>
						
						<input type="checkbox" name="Hautes-Alpes" id="checkbox-6b" />
						<label for="checkbox-6b">Hautes-Alpes</label>
						
						<input type="checkbox" name="Rhône" id="checkbox-7b"/>
						<label for="checkbox-7b">Rhône</label>
						
						<input type="checkbox" name="Savoie" id="checkbox-8b" />
						<label for="checkbox-8b">Savoie</label>
						
						<input type="checkbox" name="Haute-Savoie" id="checkbox-9b"/>
						<label for="checkbox-9b">Haute-Savoie</label>
						
						<input type="checkbox" name="Ain" id="checkbox-10b"/>
						<label for="checkbox-10b">Ain</label>
						
						<input type="checkbox" name="Isère" id="checkbox-11b"/>
						<label for="checkbox-11b">Isère</label>
						
						<input type="checkbox" name="Drôme" id="checkbox-12b"/>
						<label for="checkbox-12b">Drôme</label>
					</div>
					<div data-role="collapsible">
						<h3><?= _("Italy") ?></h3>
						<input type="checkbox" name="Aosta" id="checkbox-13b"/>
						<label for="checkbox-13b">Aosta</label>
						
						<input type="checkbox" name="Cuneo" id="checkbox-14b"/>
						<label for="checkbox-14b">Cuneo</label>
						
						<input type="checkbox" name="Torino" id="checkbox-15b"/>
						<label for="checkbox-15b">Torino</label>
						
						<input type="checkbox" name="Imperia" id="checkbox-16b"/>
						<label for="checkbox-16b">Imperia</label>
						
						<input type="checkbox" name="Genova" id="checkbox-17b"/>
						<label for="checkbox-17b">Genova</label>
						
						<input type="checkbox" name="Biella" id="checkbox-18b"/>
						<label for="checkbox-18b">Biella</label>
						
						<input type="checkbox" name="Vercelli" id="checkbox-19b"/>
						<label for="checkbox-19b">Vercelli</label>
						
						<input type="checkbox" name="Asti" id="checkbox-20b"/>
						<label for="checkbox-20b">Asti</label>
						
						<input type="checkbox" name="Alessandria" id="checkbox-21b"/>
						<label for="checkbox-21b">Alessandria</label>
						
						<input type="checkbox" name="Savona" id="checkbox-22b"/>
						<label for="checkbox-22b">Savona</label>
					
					</div>
					<div data-role="collapsible">
						<h3><?= _("Other") ?></h3>
					
						<input type="checkbox" name="Monaco" id="checkbox-23b"/>
						<label for="checkbox-23b">Monaco</label>
						
						<input type="checkbox" name="Suisse" id="checkbox-24b"/>
						<label for="checkbox-24b">Suisse</label>
					</div>
				
				
				
				
				
				
				</div>
				
		    </fieldset>

			<label for="textContent"><b><?= _('Description') ?>:</b></label>
			<input type="text" name="title" placeholder="<?= _('title') ?>" data-mini="true" data-inline="true" value="" />
			<textarea id="CLEeditor" id="textContent" name="text"></textarea>

			<div style="text-align: center;" >
				<input type="submit" data-theme="b"  data-inline="true" data-icon="check" value="Insérer" />
			</div>
		</form>
	</div>
</div>


<? include("infos.php"); ?>

<? include("footer.php"); ?>