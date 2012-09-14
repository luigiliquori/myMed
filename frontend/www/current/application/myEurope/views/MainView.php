<? include("header.php"); ?>

<?
 function tab_bar($activeTab) {
 	tabs($activeTab, array(
 		array("#infos", "About European programs", "info-sign"),
 		array("#home", "Partenariats", "retweet"),
 		array("#blogs", "Bonnes pratiques", "comments"),
 		array("#profile", $_SESSION['user']->name, "user")
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
			<a href="#search" type="button" data-icon="fasearch" class="mymed-huge-button"><?= _('Search a partnership offer') ?></a>
		</div>
		
		<div data-role="fieldcontain">
			<a href="#post" type="button" data-icon="share" class="mymed-huge-button"><?= _('Insert a partnership offer') ?></a>
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
			
			<br />
			
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup">
				<legend><?= _('Offer Themes') ?>:</legend>
				
				<input type="checkbox" id="checkbox-all" />
				<label for="checkbox-all"><?= _('All') ?></label>
				
				<? foreach (Categories::$themes as $k=>$v): ?>
					<input type="checkbox" name="t[]" value="<?= $k ?>" id="checkbox-<?= $k ?>"/>
					<label for="checkbox-<?= $k ?>"><?= $v ?></label>
				<? endforeach; ?>

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
						
						<? foreach (Categories::$places_fr as $k=>$v): ?>
							<input type="checkbox" name="pf[]" value="<?= $v ?>" id="checkbox-f<?= $k ?>"/>
							<label for="checkbox-f<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
						
					</div>
					<div data-role="collapsible">
						<h3><?= _("Italy") ?></h3>
						<input type="checkbox" id="checkbox-all2" />
						<label for="checkbox-all2"><?= _('All') ?></label>
						
						<? foreach (Categories::$places_it as $k=>$v): ?>
							<input type="checkbox" name="pi[]" value="<?= $v ?>" id="checkbox-i<?= $k ?>"/>
							<label for="checkbox-i<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
						
					</div>
					<div data-role="collapsible">
						<h3><?= _("Other") ?></h3>
					
						<? foreach (Categories::$places_ot as $k=>$v): ?>
							<input type="checkbox" name="po[]" value="<?= $v ?>" id="checkbox-o<?= $k ?>"/>
							<label for="checkbox-o<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
						
					</div>
				
				</div>
				
		    </fieldset>
		    </div>
			<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<legend><?= _('Category of searched partners') ?>:</legend>
				<? foreach (Categories::$roles as $k=>$v): ?>
					<input type="checkbox" name="r[]" value="<?= $k ?>" id="checkbox-<?= $k ?>"/>
					<label for="checkbox-<?= $k ?>"><?= $v ?></label>
				<? endforeach; ?>
		    </fieldset>
		    </div>
		      <div data-role="fieldcontain">
				<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
				<select name="c" id="call">
					<? foreach (Categories::$calls as $k=>$v): ?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach; ?>
				</select>
			</div>
		    <div data-role="fieldcontain">
				<label for="textinputs1"><?= _('keywords') ?>: </label>
				<input id="textinputs1" name="k" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
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

			<input type="hidden" name="r" value="<?= $_SESSION['myEuropeProfile']->role ?>" />
			
			<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<legend><?= _('Themes') ?>:</legend>
								
				<? foreach (Categories::$themes as $k=>$v): ?>
					<input type="checkbox"  name="t[]" value="<?= $k ?>" id="checkbox-<?= $k ?>"/>
					<label for="checkbox-<?= $k ?>"><?= $v ?></label>
				<? endforeach; ?>
				
		    </fieldset>
		    </div>
		    
			<div data-role="fieldcontain">
		 	<fieldset data-role="controlgroup">
				<legend><?= _('Areas') ?>:</legend>
				
				<div data-role="collapsible-set">
				
				
					<div data-role="collapsible">
						<h3><?= _("France") ?></h3>
						
						<? foreach (Categories::$places_fr as $k=>$v): ?>
							<input type="checkbox" name="pf[]" value="<?= $v ?>" id="checkbox-f<?= $k ?>"/>
							<label for="checkbox-f<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>

					</div>
					<div data-role="collapsible">
						<h3><?= _("Italy") ?></h3>
						
						<? foreach (Categories::$places_it as $k=>$v): ?>
							<input type="checkbox" name="pi[]" value="<?= $v ?>" id="checkbox-i<?= $k ?>"/>
							<label for="checkbox-i<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
						
					</div>
					<div data-role="collapsible">
						<h3><?= _("Other") ?></h3>
					
						<? foreach (Categories::$places_ot as $k=>$v): ?>
							<input type="checkbox" name="po[]" value="<?= $v ?>" id="checkbox-o<?= $k ?>"/>
							<label for="checkbox-o<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
					</div>
				
				</div>
		    </fieldset>
		    </div>
		    <div data-role="fieldcontain">
				<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
				<select name="c" id="call">
					<? foreach (Categories::$calls as $k=>$v): ?>
						<option value="<?= $k ?>"><?= $v ?></option>
					<? endforeach; ?>
				</select>
			</div>
		    
			<div data-role="fieldcontain">
				<label for="textinputp1"><?= _('Keywords') ?>: </label>
				<input id="textinputp1" name="k" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
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