<? include("header.php"); ?>
<link rel="stylesheet" type="text/css" href="../../lib/jquery/CLEeditor/jquery.cleditor.css" />
<script type="text/javascript" src="../../lib/jquery/CLEeditor/jquery.cleditor.min.js"></script>
<script type="text/javascript" src="../../lib/jquery/CLEeditor/startCLE.js"> </script>
<?
function tab_bar_white($activeTab) {
	tabs_white(array(
			"share" => array(_('Share'), "plus"),
			"home" => array('<span class="mainTab">'.APPLICATION_NAME.'</span>', "myEurope"),
			"admin" => array(_('Admin'), "gear"),
			"profile" => array(_('Profile'), "profile"),
		),
		$activeTab);
} 
?>

<div data-role="page" id="home">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("home") ?>
	</div>
	<div data-role="content" style="text-align:center;">
		<h3 class="ui-link"><?= _('Partnership') ?>:</h3>
		<div data-role="controlgroup"  data-type="horizontal">
			<a href="#search" type="button" data-theme="b" style="width:50%;"><br /><span style="color: yellow;"><?= _('Search a partner') ?></span><br />&nbsp;</a>
			<a href="#post" type="button" data-theme="b" style="width:49%;" rel="external"><br /><span style="color: yellow;"><?= _('Insert an offer') ?></span><br />&nbsp;</a>
		</div>
		
		<h3 class="ui-link"><?= _('Infomations') ?>:</h3>
		
			<a href="#infos" style="width:30%;min-width:180px;" class="wrap"
			type="button" data-inline="true" data-theme="d"><?= _('About European programs') ?><span style="font-weight: lighter;"> 2014-2020</span></a>
			<a href="?action=Blog&blog=alcotra"  style="width:30%;min-width:180px;" class="wrap"
			type="button"  data-theme="d" data-inline="true"><?= _('Alcotra Blog') ?><span style="font-weight: lighter;"> 2014-2020</span></a>
			<a href="?action=Blog&blog=testers" style="width:30%;min-width:180px;" class="wrap"
			type="button"  data-theme="d" data-inline="true"><?= _('Beta Testers Blog') ?></a>
		
		<br /><br />
		<select data-theme="c" data-mini="true" name="slider" id="flip-d" data-role="slider"
			onchange="if ($(this).val()==1){$('#AboutContent').fadeOut('slow');} else {$('#AboutContent').fadeIn('slow')};">
			<option value="1"><?= _("About") ?></option>
			<option value="0"><?= _("About") ?></option>
		</select>
		<div id="AboutContent" style="display:none;">
			<?= about(); ?>
		</div>
	</div>
</div>

<div data-role="page" id="profile">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_white("profile") ?>
		<? include("notifications.php")?>
	</div>

	<div data-role="content" >
		<br />
		<span class="label"><?= _("Role") ?>:</span> <?= $_SESSION['myEuropeProfile']->role ?><br />
		<span class="label"><?= _("Activity") ?>:</span> <?= $_SESSION['myEuropeProfile']->activity ?><br />
		<span class="label"><?= _("Email") ?>:</span> <?= $_SESSION['myEuropeProfile']->email ?><br />
		<span class="label"><?= _("Address") ?>:</span> <?= $_SESSION['myEuropeProfile']->address ?><br />
		<span class="label"><?= _("Description") ?>:</span> <?= $_SESSION['myEuropeProfile']->desc ?><br />
		
		<a type="button" data-mini="true" href="?action=ExtendedProfile&edit=false" data-inline="true" data-theme="c" data-icon="grid">Modifer</a>
		<br />
		<br />
		Réputation: 
		<? for($i=20; $i<=100; $i+=20) : ?>
			<a data-theme="<?= ($_SESSION['myEuropeRep']['rep'] >= $i)?'e':'c' ?>" data-role="button" data-iconpos="notext" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"></a>
		<? endfor ?>&nbsp;&nbsp;
		<?= $_SESSION['myEuropeRep']['up'] ?> <img src="./img/up.png" style="height: 22px;vertical-align: middle;"/>
		<?= $_SESSION['myEuropeRep']['down'] ?> <img src="./img/down.png" style="height: 22px;vertical-align: middle;"/>
		
		<br /><br />
		<span> Langue: </span>&nbsp;&nbsp;
		<fieldset data-role="controlgroup" data-mini="true" data-type="horizontal" style="display:inline-block;vertical-align: middle;">
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-a" value="fr" <?= $_SESSION["user"]->lang == "fr"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _('French') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-b" value="it" <?= $_SESSION["user"]->lang == "it"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _('Italian') ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-e" value="en" <?= $_SESSION["user"]->lang == "en"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _('English') ?></label>
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
			<span><?= _('Restricted page for admins') ?></span><br />
			<? if ($_SESSION['myEuropeProfile']->permission<=1) {?>
				<a href="#home" type="button" data-inline="true" data-theme="r" data-icon="back"><?= _('Back') ?></a>
			<? } else { ?>
				<a href="./?action=Admin" data-ajax="false" type="button" data-inline="true" data-theme="g"><?= _('Access') ?></a>
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
		<a type="button" data-ajax="false" data-mini="true" data-inline="true" href="?action=search&namespace=part">Voir les offres les mieux notées</a><br /><br />

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
			
	
		 	<fieldset data-role="controlgroup" id="themecheckboxes">
				<legend><b><?= _('Themes') ?>:</b></legend>
				
				<input type="checkbox" id="checkbox-all" />
				<label for="checkbox-all"><?= _('All') ?></label>
				
				<input type="checkbox" name="education" id="checkbox-1a"/>
				<label for="checkbox-1a">Education, culture & sport</label>

				<input type="checkbox" name="social" id="checkbox-2a"/>
				<label for="checkbox-2a">Emploi, affaires sociales & égalité des chances</label>
				
				<input type="checkbox" name="entreprise" id="checkbox-3a"/>
				<label for="checkbox-3a">Entreprises & innovation</label>

				<input type="checkbox" name="envoronnement" id="checkbox-4a"/>
				<label for="checkbox-4a">Environnement, énergie & transports</label>
				
				<input type="checkbox" name="agriculture" id="checkbox-5a" />
				<label for="checkbox-5a">Agriculture</label>

				<input type="checkbox" name="peche" id="checkbox-6a" />
				<label for="checkbox-6a">Pêche</label>
				
				<input type="checkbox" name="transfontalier" id="checkbox-7a"/>
				<label for="checkbox-7a">Cohésion économique et sociale</label>

				<input type="checkbox" name="recherche" id="checkbox-8a" />
				<label for="checkbox-8a">Recherche</label>
				
				<input type="checkbox" name="santé" id="checkbox-9a" />
				<label for="checkbox-9a">Santé & protection des consommateurs</label>
				
		    </fieldset>
		    <input type="search" name="themes" id="text" placeholder="autres mots clés" value="" />
		    
			<br />
		 	<fieldset data-role="controlgroup">
				<legend><b>Pays:</b></legend>
				<input type="checkbox" name="france" id="checkbox-1b" checked="checked"/>
				<label for="checkbox-1b">France</label>

				<input type="checkbox" name="italy" id="checkbox-2b"/>
				<label for="checkbox-2b">Italie</label>
				
		    </fieldset>
		    
		    <input type="search" name="places" id="text" placeholder="autres lieux" value="" /> 
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
		<form action="./" method="post" id="publishForm" data-ajax="false">
				
			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="namespace" value="part" />
			
				
		    
			
			<fieldset data-role="controlgroup">
				<legend><b>Thèmes:</b></legend>
								
				<input type="checkbox" name="education" id="checkbox-1a"/>
				<label for="checkbox-1a">Education, culture & sport</label>

				<input type="checkbox" name="social" id="checkbox-2a"/>
				<label for="checkbox-2a">Emploi, affaires sociales & égalité des chances</label>
				
				<input type="checkbox" name="entreprise" id="checkbox-3a"/>
				<label for="checkbox-3a">Entreprises & innovation</label>

				<input type="checkbox" name="envoronnement" id="checkbox-4a"/>
				<label for="checkbox-4a">Environnement, énergie & transports</label>
				
				<input type="checkbox" name="agriculture" id="checkbox-5a" />
				<label for="checkbox-5a">Agriculture</label>

				<input type="checkbox" name="peche" id="checkbox-6a" />
				<label for="checkbox-6a">Pêche</label>
				
				<input type="checkbox" name="transfontalier" id="checkbox-7a"/>
				<label for="checkbox-7a">Cohésion économique et sociale</label>

				<input type="checkbox" name="recherche" id="checkbox-8a" />
				<label for="checkbox-8a">Recherche</label>
				
				<input type="checkbox" name="santé" id="checkbox-9a" />
				<label for="checkbox-9a">Santé & protection des consommateurs</label>
				
		    </fieldset>
		    <input type="search" name="themes" id="text" placeholder="autres mots clés" value="" />
		    
			<br />
		 	<fieldset data-role="controlgroup">
				<legend><b>Pays:</b></legend>
				<input type="checkbox" name="france" id="checkbox-1b" checked="checked"/>
				<label for="checkbox-1b">France</label>

				<input type="checkbox" name="italy" id="checkbox-2b"/>
				<label for="checkbox-2b">Italie</label>
				
		    </fieldset>
		    
		    <input type="search" name="places" id="text" placeholder="autres lieux" value="" /> 
			<br />

			<label for="textContent"><b>Contenu:</b></label>
			<textarea id="CLEeditor" id="textContent" name="text"></textarea>

			<div style="text-align: center;" >
				<input type="submit" data-theme="b"  data-inline="true" data-icon="check" value="Insérer" />
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="blogAlcotra">
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	      	<ul>
	      		<li><a href="./" data-icon="back"><?= _("Back") ?></a></li>
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
	      		<li><a href="./" data-icon="back"><?= _("Back") ?></a></li>
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
		</ul>

		
		<br />
		Répondre:<textarea></textarea>
		<input type="submit" data-theme="b"  data-mini="true" data-inline="true" value="Publier" />
		
	</div>
</div>

<? include("infos.php"); ?>

<? include("footer.php"); ?>