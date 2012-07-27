<? include("header.php"); ?>
<link rel="stylesheet" type="text/css" href="../../lib/jquery/CLEeditor/jquery.cleditor.css" />
<script type="text/javascript" src="../../lib/jquery/CLEeditor/jquery.cleditor.min.js"></script>
<script type="text/javascript" src="../../lib/jquery/CLEeditor/startCLE.js"> </script>
<?
function tab_bar_white($activeTab) {
	tabs_white(array(
			"share" => array("Partagez", "plus"),
			"home" => array("myEurope", "myEurope"),
			"rep" => array("Reputation Area", "star"),
			"profile" => array("Profil", "profile"),
		),
		$activeTab);
} 
?>

<div data-role="page" id="home">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("home") ?>
	</div>
	<div data-role="footer" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c" data-iconpos="left">
			<ul>
				<li><a href="#admin" data-icon="gear">Admin</a></li>
			</ul>
		</div>
	</div>
	<div data-role="content" style="text-align:center;">
		<h3 class="ui-link">Partenariats:</h3>
		<div data-role="controlgroup"  data-type="horizontal">
			<a href="#search" type="button" data-theme="e" style="width:50%;"><br />Rechercher une offre<br />&nbsp;</a>
			<a href="#post" style="width:49%;" type="button" data-theme="e" rel="external"><br />Déposer une offre<br />&nbsp;</a>
		</div>
		
		<h3 class="ui-link">Informations:</h3>
		<div data-role="controlgroup"  data-type="horizontal">
			<a href="#infos" style="width:33%;" type="button"  data-theme="d"><br />S'informer <span style="font-weight: lighter;">sur les programmes 2014-2020</span><br />&nbsp;</a>
			<a href="#blogAlcotra"  style="width:33%;"
			type="button"  data-theme="d"><br />Blog Alcotra<span style="font-weight: lighter;">: futur programme 2014-2020</span><br />&nbsp;</a>
			<a href="#blogTest"  style="width:33%;"
			type="button"  data-theme="d"><br />Blog béta testeurs<br />&nbsp;</a>
		</div>
		<br />
		<select data-theme="c" data-mini="true" name="slider" id="flip-d" data-role="slider"
			onchange="if ($(this).val()==1){$('#AboutContent').fadeOut('slow');} else {$('#AboutContent').fadeIn('slow')};">
			<option value="1"><?= _("A propos") ?></option>
			<option value="0"><?= _("A propos") ?></option>
		</select>
		<div id="AboutContent" style="display:none;">
			<?= _(about()) ?>
		</div>
	</div>
</div>

<div data-role="page" id="profile">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("profile") ?>
	</div>

	<div data-role="content" >
		<br />
		Rôle: <?= $_SESSION['myEuropeProfile']->role ?><br />
		activité: <?= $_SESSION['myEuropeProfile']->activity ?><br />
		Email: <?= $_SESSION['myEuropeProfile']->email ?><br />
		Adresse: <?= $_SESSION['myEuropeProfile']->address ?><br />
		<br />
		<a type="button" data-mini="true" href="?action=ExtendedProfile&edit=false" data-inline="true" data-theme="c" data-icon="gear">Modifer</a>
	
		<br />
		
		<br />
		
		<span> Langue: </span>&nbsp;&nbsp;
		<fieldset data-role="controlgroup" data-mini="true" data-type="horizontal" style="display:inline-block;vertical-align: middle;">
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-a" value="fr" <?= $_SESSION["user"]->lang == "fr"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _('Français') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-b" value="it" <?= $_SESSION["user"]->lang == "it"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _('Italien') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-e" value="en" <?= $_SESSION["user"]->lang == "en"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _('Anglais') ?></label>
		</fieldset>
	</div>
</div>

<div data-role="page" id="admin">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("admin") ?>
	</div>
	<div data-role="content">
		<br />
		<div style="text-align:center;">
			<span>Page réservée aux utilisateurs Admin</span><br />
			<? if ($_SESSION['myEuropeProfile']->permission<=1) {?>
				<a href="#home" type="button" data-inline="true" data-theme="r" data-icon="back">Retour</a>
			<? } else { ?>
				<a href="./?action=Admin" data-ajax="false" type="button" data-inline="true" data-theme="g">Accéder</a>
			<? } ?>
		</div>
	</div>
</div>

<div data-role="page" id="share">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("share") ?>
	</div>
	<div data-role="content" style="text-align:center;">
		
			Partagez <?= APPLICATION_NAME ?>:<br /><br />
			<div class="addthis_toolbox addthis_floating_style addthis_32x32_style" style="position:relative; margin: auto; background: transparent;">
				
				<a class="addthis_button_google_plusone_share"></a>
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_compact"></a>
			</div>
		</div>
	</div>
</div>

<div data-role="page" id="rep">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("rep") ?>
	</div>
	<div data-role="content">


		<b>Selectionnez parmi les offres de partenaires vus:</b><br/>

		<input type="checkbox" name="pr1" id="checkbox-5a" />
		<label for="checkbox-5a">Offre1 <a href="">100%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a></label>
		
		<input type="checkbox" name="pr2" id="checkbox-6a" />
		<label for="checkbox-6a">Offre4 <a href="">90%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a></label>
		
		<input type="checkbox" name="pr3" id="checkbox-7a"/>
		<label for="checkbox-7a">Offre3 <a href="">80%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a><span></label>

		<input type="checkbox" name="pr4" id="checkbox-8a" />
		<label for="checkbox-8a">Offre2 <a href="">75%</a><a style="float:right;" href="./?action=details&id=21">Lien vers l'offre</a></label>
	
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
		<a type="button" data-ajax="false" data-mini="true" data-inline="true" href="?action=search&namespace=part">Voir les offres les mieux notées</a><br /><br />

	</div>
</div>


<div data-role="page" id="search">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2click(
				"Chercher un partenaire",
				"$('#searchForm').submit();",
				"search") ?>
	</div>
	
	<div data-role="content">
		<form action="./" id="searchForm" data-ajax="false">
		
			<input type="hidden" name="action" value="Search" />
			<input type="hidden" name="namespace" value="part" />
			
	
		 	<fieldset data-role="controlgroup" id="themecheckboxes">
				<legend><b>Thèmes:</b></legend>
				
				<input type="checkbox" id="checkbox-all" />
				<label for="checkbox-all">Tous</label>
				
				<input type="checkbox" name="themeedu" id="checkbox-1a"/>
				<label for="checkbox-1a">Education, culture & sport</label>

				<input type="checkbox" name="themeemp" id="checkbox-2a"/>
				<label for="checkbox-2a">Emploi, affaires sociales & égalité des chances</label>
				
				<input type="checkbox" name="themeent" id="checkbox-3a"/>
				<label for="checkbox-3a">Entreprises & innovation</label>

				<input type="checkbox" name="themeenv" id="checkbox-4a"/>
				<label for="checkbox-4a">Environnement, énergie & transports</label>
				
				<input type="checkbox" name="themeagr" id="checkbox-5a" />
				<label for="checkbox-5a">Agriculture</label>

				<input type="checkbox" name="themepec" id="checkbox-6a" />
				<label for="checkbox-6a">Pêche</label>
				
				<input type="checkbox" name="themesoc" id="checkbox-7a"/>
				<label for="checkbox-7a">Cohésion économique et sociale</label>

				<input type="checkbox" name="themerec" id="checkbox-8a" />
				<label for="checkbox-8a">Recherche</label>
				
				<input type="checkbox" name="themesan" id="checkbox-9a" />
				<label for="checkbox-9a">Santé & protection des consommateurs</label>
				
		    </fieldset>
			<br />
		 	<fieldset data-role="controlgroup">
				<legend><b>Pays:</b></legend>
				<input type="checkbox" name="regfr" id="checkbox-1a" checked="checked"/>
				<label for="checkbox-1a">France</label>

				<input type="checkbox" name="regit" id="checkbox-2a"/>
				<label for="checkbox-2a">Italie</label>
				
		    </fieldset>
			<br />
			<label for="search"><b>ou Mots clés:</b></label>
			<input type="search" name="q" id="search" value="projet test" />
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
				"Insérer une offre",
				"$('#publishForm').submit();",
				"gear") ?>
	</div>

	<div data-role="content">
		<form action="./" method="post" id="publishForm" data-ajax="false">
				
			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="namespace" value="part" />
			
		 	<fieldset data-role="controlgroup">
				<legend><b>Thèmes:</b></legend>
				<input type="checkbox" name="themeedu" id="checkbox-1a"/>
				<label for="checkbox-1a">Education, culture & sport</label>

				<input type="checkbox" name="themeemp" id="checkbox-2a"/>
				<label for="checkbox-2a">Emploi, affaires sociales & égalité des chances</label>
				
				<input type="checkbox" name="themeent" id="checkbox-3a"/>
				<label for="checkbox-3a">Entreprises & innovation</label>

				<input type="checkbox" name="themeenv" id="checkbox-4a"/>
				<label for="checkbox-4a">Environnement, énergie & transports</label>
				
				<input type="checkbox" name="themeagr" id="checkbox-5a" />
				<label for="checkbox-5a">Agriculture</label>

				<input type="checkbox" name="themepec" id="checkbox-6a" />
				<label for="checkbox-6a">Pêche</label>
				
				<input type="checkbox" name="themesoc" id="checkbox-7a"/>
				<label for="checkbox-7a">Cohésion économique et sociale</label>

				<input type="checkbox" name="themerec" id="checkbox-8a" />
				<label for="checkbox-8a">Recherche</label>
				
				<input type="checkbox" name="themesan" id="checkbox-9a" />
				<label for="checkbox-9a">Santé & protection des consommateurs</label>
				
		    </fieldset>
			<br />
		 	<fieldset data-role="controlgroup">
				<legend><b>Pays:</b></legend>
				<input type="checkbox" name="regfr" id="checkbox-1a" checked="checked"/>
				<label for="checkbox-1a">France</label>

				<input type="checkbox" name="regit" id="checkbox-2a"/>
				<label for="checkbox-2a">Italie</label>
				
		    </fieldset>
			<br />
			<label for="search"><b>ou Mots clés:</b></label>
			<input type="search" name="q" id="search" value="projet test" />

			<label for="textContent"><b>Contenu:</b></label>
			<textarea id="CLEeditor" id="textContent" name="text"></textarea>

			<div style="text-align: center;" >
				<input type="submit" data-theme="b"  data-inline="true" value="Insérer" />
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="blogAlcotra">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a href="./" data-icon="back">Retour</a></li>
	      		<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
	      	</ul>
      	</div>
	</div>
	
	<div data-role="content">
		<h3 class="ui-link" style="text-align: center;">Blog Alcotra</h3>
		<ul data-role="listview" data-theme="d" data-inset="true">
			<li>
				<h3>myEurope Team</h3>
				<p><strong>Bienvenue à tous</strong></p>
				<p>Blog porte sur les orientations du programme Alcotra 2014-2020 <br />
				voir <a href="#alcotra">#alcotra</a> pour les infos détaillées</p>
				<p class="ui-li-aside"><strong>8:24</strong> 25/7/2012</p>
			</li>
			<li>
				<h3>Stephen Weber</h3>
				<p><strong>Demo Planning</strong></p>
				<p>..............................................................<br />
				Preum's</p>
				<p class="ui-li-aside"><strong>19:18</strong> 25/7/2012</p>
			</li>
		</ul>

		
		<br />
		Répondre:<textarea></textarea>
		<input type="submit" data-theme="b"  data-mini="true" data-inline="true" value="Publier" />
	</div>
</div>

<div data-role="page" id="blogTest">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a href="./" data-icon="back">Retour</a></li>
	      		<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
	      	</ul>
      	</div>
	</div>
	
	<div data-role="content">
		<h3 class="ui-link" style="text-align: center;">Blog bétas testeurs</h3>
		<ul data-role="listview" data-theme="d" data-inset="true">
			<li>
				<h3>myEurope Team</h3>
				<p><strong>Bienvenue à tous</strong></p>
				<p>Ce blog est destiné à collecter diverses remarques, et BUGs dans l'application myEurope</p>
				<p class="ui-li-aside"><strong>8:24</strong> 25/7/2012</p>
			</li>
			<li>
				<h3>Stephen Weber</h3>
				<p><strong>Demo Planning</strong></p>
				<p>..............................................................<br />
				Preum's</p>
				<p class="ui-li-aside"><strong>19:18</strong> 25/7/2012</p>
			</li>
		</ul

		
		<br />
		Répondre:<textarea></textarea>
		<input type="submit" data-theme="b"  data-mini="true" data-inline="true" value="Publier" />
		
	</div>
</div>

<? include("infos.php"); ?>

<? include("footer.php"); ?>