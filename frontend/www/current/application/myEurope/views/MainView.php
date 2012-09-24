<? include("header.php"); ?>


<div data-role="page" id="home">

	<? tab_bar_default("#home") ?>
	<? include("notifications.php"); ?>
	<div data-role="content" style="text-align:center;">
		
		<br />
		<div data-role="fieldcontain">
			<a href="#search" type="button" class="mymed-huge-button"><?= _('Search a partnership offer') ?></a>
		</div>
		
		<div data-role="fieldcontain">
			<a href="#post" type="button" class="mymed-huge-button"><?= _('Insert a partnership offer') ?></a>
		</div>
	</div>
</div>

<div data-role="page" id="profile">

	<? tab_bar_default("#profile") ?>
	<div data-role="content" style="text-align:center;">
		
		
		<?php if($_SESSION['user']->profilePicture != ""): ?>
			<a href="#updatePicPopup" data-rel="popup"><img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="80"></a>
		<?php else: ?>
			<a href="#updatePicPopup" data-rel="popup"><img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="80"></a>
		<?php endif; ?>
		
		
		<div data-role="popup" id="updatePicPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<div style="display: inline-block;">
				<input type="text" id="picUrl" placeholder="Picture's url" value="http://cdn.walyou.com/wp-content/uploads//2010/12/facebook-profile-picture-no-pic-avatar.jpg" data-inline="true" />
			</div>
			<a onclick="$('#updatePicPopup').popup('close');updateProfile('profilePicture', $('#picUrl').val());" data-role="button" data-theme="d" data-mini="true" data-icon="ok" data-inline="true"><?= _("Update") ?></a>
		</div>
		
		<div style="display:inline-block; margin-left: 15px; vertical-align: 25%; color: white; font-weight: bold; font-size: 14pt; text-align: center;">
			<?= $_SESSION['user']->firstName ?> <?= $_SESSION['user']->lastName ?> 
		</div>
		
		<? $_SESSION['myEuropeProfile']->renderProfile(); ?>

		<br />
		<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
		<br />
		<? if ($_SESSION['myEurope']->permission > 1): ?>
			<a href="?action=Admin" data-role="button" data-icon="gear" data-inline="true"><?= _('Admin') ?></a>
		<? endif; ?>
		<!-- <a data-role="button" href="?action=logout" rel="external" data-icon="signout" data-inline="true"><?= _('Log Out') ?></a>  -->
		
	</div>
</div>

<div data-role="page" id="blogs">

	<? tab_bar_default("#blogs") ?>
	<div data-role="content" style="text-align:center;">
		<ul data-role="listview" data-inset="true" data-filter="true" >
			<li data-role="list-divider"><?= _('Journal des bonnes pratiques') ?></li>
			<li>
				<a href="?action=Blog&blog=Bonnes Pratiques" rel="external" data-icon="pushpin" class="mymed-huge-button"><?= _('Bonnes Pratiques Générales') ?></a>
			</li>
			<li>
				<a href="?action=Blog&blog=Par quoi commencer ?" rel="external" data-icon="pushpin" class="mymed-huge-button"><?= _('Par quoi commencer ?') ?></a>
			</li>
			<li>
				<a href="?action=Blog&blog=Pourquoi chercher un partenariat europeen ?" rel="external" data-icon="pushpin" class="mymed-huge-button"><?= _('Pourquoi chercher un partenariat européen ?') ?></a>
			</li>
			<li>
				<a href="?action=Blog&blog=Vos temoignages" rel="external" data-icon="pushpin" class="mymed-huge-button"><?= _('Vos témoignages') ?></a>
			</li>
			<li>
				<a href="?action=Blog&blog=Quelques idees" rel="external" data-icon="pushpin" class="mymed-huge-button"><?= _('Quelques idées') ?></a>
			</li>
			<li data-role="list-divider"><?= _('Journal des "Beta" testeurs de myEurope') ?></li>
			<li>
				<a href="?action=Blog&blog=myEurope"  rel="external" class="mymed-huge-button"><?= _('Bugs et problèmes rencontrés') ?></a>
			</li>
			<li>
				<a href="?action=Blog&blog=Ameliorations proposees"  rel="external" class="mymed-huge-button"><?= _('Améliorations proposées') ?></a>
			</li>
			<li>
				<a href="?action=Blog&blog=Discussion libre"  rel="external" class="mymed-huge-button"><?= _('Discussion libre') ?></a>
			</li>
		</ul>
		
		<? if ($_SESSION['myEurope']->permission > 1): ?>
			<div data-role="fieldcontain">
				<a href="#createPopup" data-rel="popup" data-inline="true" type="button" data-icon="faplus"> <?= _("Create a new blog") ?></a>
			</div>
		<? endif; ?>
		
		<div data-role="popup" id="createPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<input type="text" id="blogName" placeholder="Blog's name" data-inline="true" />
			<a onclick="$('#createPopup').popup('close');" data-role="button" data-theme="d" data-icon="ok" data-inline="true"><?= _("Create") ?></a>
		</div>
	</div>
</div>

<div data-role="page" id="about">

	<? tab_bar_default("#about") ?>
	<div data-role="content">
		
		<br />
		<?= about() ?>
	</div>
</div>

<div data-role="page" id="admin">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tab_bar_default("#admin") ?>
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

	<? tabs_simple(array('Recherche de partenaire')); ?>
	<div data-role="content">
		<form action="" id="searchForm" data-ajax="false">
		
			<input type="hidden" name="action" value="Search" />
			
			<br />
			
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
				<div  data-role="collapsible" data-collapsed="false">
					<h3><?= _('Offer Themes') ?>:</h3>
				 	<fieldset data-role="controlgroup">
						<? foreach (Categories::$themes as $k=>$v): ?>
							<input type="checkbox" name="t[]" value="<?= $k ?>" id="checkbox-<?= $k ?>"/>
							<label for="checkbox-<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
				    </fieldset>
				</div>
				<div data-role="collapsible" data-collapsed="true">
					<h3><?= _('Areas') ?>:</h3>
					<fieldset data-role="controlgroup">
						<div data-role="collapsible-set" data-mini="true">
							<div data-role="collapsible" data-collapsed="false">
								<h3><?= _("France") ?></h3>	
								<? foreach (Categories::$places_fr as $k=>$v): ?>
									<input type="checkbox" name="pf[]" value="<?= $v ?>" id="checkbox-f<?= $k ?>"/>
									<label for="checkbox-f<?= $k ?>"><?= $v ?></label>
								<? endforeach; ?>
							</div>
							<div data-role="collapsible" data-collapsed="true">
								<h3><?= _("Italy") ?></h3>
								<? foreach (Categories::$places_it as $k=>$v): ?>
									<input type="checkbox" name="pi[]" value="<?= $v ?>" id="checkbox-i<?= $k ?>"/>
									<label for="checkbox-i<?= $k ?>"><?= $v ?></label>
								<? endforeach; ?>	
							</div>
							<div data-role="collapsible" data-collapsed="true">
								<h3><?= _("Other") ?></h3>
								<? foreach (Categories::$places_ot as $k=>$v): ?>
									<input type="checkbox" name="po[]" value="<?= $v ?>" id="checkbox-o<?= $k ?>"/>
									<label for="checkbox-o<?= $k ?>"><?= $v ?></label>
								<? endforeach; ?>	
							</div>
						</div>
					</fieldset>
			    </div>
			    
				<div data-role="collapsible" data-collapsed="true">
					<h3><?= _('Category of searched partners') ?></h3>
					<fieldset data-role="controlgroup">
						<? foreach (Categories::$roles as $k=>$v): ?>
							<input type="checkbox" name="r[]" value="<?= $k ?>" id="checkbox-<?= $k ?>"/>
							<label for="checkbox-<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
				    </fieldset>
	
				    
				    <div data-role="fieldcontain">
				   		<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
						<select name="c" id="call">
							<? foreach (Categories::$calls as $k=>$v): ?>
								<option value="<?= $k ?>"><?= $v ?></option>
							<? endforeach; ?>
						</select>
					</div>
					
					<div data-role="fieldcontain">
			    		<label for="textinputs1"><?= _('keywords') ?>:</label>
			    		<input id="textinputs1" name="k" placeholder="<?= _('separated by a space, comma, plus') ?>" value='' type="text" />
			    	</div>
					
			    </div>
			
			</div>

			<br />
			<div data-role="popup" id="helpPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
				<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
				<p> 
					Si vous laissez tous les champs <b>vides</b> (non cochés), vous obtenez toutes les offres publiées à ce jour<br><br>
					Lorsque vous laissez un ensemble de champs de recherche <b>vide</b>, la recherche est globale sur cette partie.
				</p>
			</div>
			<div style="text-align: center;" >
				<a href="#helpPopup" data-rel="popup" data-position-to="window" data-inline="true" data-theme="e" type="button" data-icon="question-sign" style="margin-right: 20px;"> <?= _("Help") ?></a>
				<input type="submit" class="ui-btn-active ui-state-persist" data-icon="search" data-inline="true" value="<?=_('Search') ?>"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="post">
	
	<? tabs_simple(array('Déposer une offre')) ?>
	<div data-role="content">
		<form action="./" method="post" id="publishForm">
				
			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="r" value="<?= $_SESSION['myEuropeProfile']->details['role'] ?>" />
			
			<div data-role="fieldcontain">
				<label for="textinputp3" class="postTitle"><b><?= _('Title') ?></b></label>
				<input id="textinputp3" class="postTitle" data-inline="true" name="title" placeholder="<?= _("partnership or project name") ?>" value='' type="text" />
			</div>
			
			<div  data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d">
				<h3>Options</h3>
				
				<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
					<div  data-role="collapsible" data-collapsed="false">
						<h3><?= _('Themes') ?>:</h3>
				 		<fieldset data-role="controlgroup">
						<? foreach (Categories::$themes as $k=>$v): ?>
							<input type="checkbox"  name="t[]" value="<?= $k ?>" id="checkbox-<?= $k ?>"/>
							<label for="checkbox-<?= $k ?>"><?= $v ?></label>
						<? endforeach; ?>
					    </fieldset>
				    </div>
				    <div data-role="collapsible" data-collapsed="true">
						<h3><?= _('Areas') ?>:</h3>
						<fieldset data-role="controlgroup">
							<div data-role="collapsible-set" data-mini="true">
								<div data-role="collapsible" data-collapsed="false">
									<h3><?= _("France") ?></h3>	
									<? foreach (Categories::$places_fr as $k=>$v): ?>
										<input type="checkbox" name="pf[]" value="<?= $v ?>" id="checkbox-f<?= $k ?>"/>
										<label for="checkbox-f<?= $k ?>"><?= $v ?></label>
									<? endforeach; ?>
								</div>
								<div data-role="collapsible" data-collapsed="true">
									<h3><?= _("Italy") ?></h3>
									<? foreach (Categories::$places_it as $k=>$v): ?>
										<input type="checkbox" name="pi[]" value="<?= $v ?>" id="checkbox-i<?= $k ?>"/>
										<label for="checkbox-i<?= $k ?>"><?= $v ?></label>
									<? endforeach; ?>	
								</div>
								<div data-role="collapsible" data-collapsed="true">
									<h3><?= _("Other") ?></h3>
									<? foreach (Categories::$places_ot as $k=>$v): ?>
										<input type="checkbox" name="po[]" value="<?= $v ?>" id="checkbox-o<?= $k ?>"/>
										<label for="checkbox-o<?= $k ?>"><?= $v ?></label>
									<? endforeach; ?>	
								</div>
							</div>
						</fieldset>
				    </div>
			  	  	 <div  data-role="collapsible" data-collapsed="true">
			  	  	 	<h3><?= _('Other options') ?>:</h3>
			  	  	 	
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
					</div>
			    
				</div>
			</div>
			
			<textarea id="CLEeditor" name="text"><h1>Votre partenariat</h1>  ...</textarea>

			<div style="text-align: center;" >
				<input type="submit" class="ui-btn-active ui-state-persist"  data-inline="true" data-icon="check" value="<?=_('Insert') ?>" />
			</div>
		</form>
	</div>
</div>


<?php 
function tabs_info($item){
	tabs_simple(array("infos", $item));
}
?>
<? if($_SESSION['user']->lang=="it"): ?>
	<? include("infos_it.php"); ?>
<? else: ?>
	<? include("infos.php"); ?>
<? endif; ?>

<? include("footer.php"); ?>