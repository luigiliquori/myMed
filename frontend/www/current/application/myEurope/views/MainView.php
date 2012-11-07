<? include("header.php"); ?>


<div data-role="page" id="home">

<? tab_bar_default("#home") ?>

<? include("notifications.php"); ?>
	<div data-role="content" >
		
		

		<br>
		
		<div style="margin: -15px;padding: 100px 0;text-align: center;">
			<a href="#search" type="button" class="mymed-huge-button" data-icon="fasearch" style="display: inline-block;"><?= _('Search a partnership offer') ?>
			</a>
			<span style="padding: 0 20px;"></span>
			<a href="#post" type="button" class="mymed-huge-button" data-icon="edit" style="display: inline-block;"><?= _('Insert a partnership offer') ?>
			</a>
		</div>
		
		<!-- <a href="/?action=store&applicationStore=myEurope#desc" rel="external" type="button" data-icon="question-sign" data-iconpos="notext" data-theme="g" style="position: absolute; top: -3px; left: 44px;"><?= _('About') ?></a> -->
		
		<div style="text-align: center;">
			<br><br>
			<a href="./"><img src="/application/<?= APPLICATION_NAME ?>/img/icon.png" style="height: 80px;" /></a>
			<br><br>
			<a href="http://www.interreg-alcotra.org/2007-2013/index.php?pg=progetto&id=139"><img alt="Alcotra" src="/system/img/logos/alcotra.png" /></a>
			<br><br>
			<i><?= _("Together") ?></i>
		</div>
		

		
		
	
	</div>
</div>

<div data-role="page" id="blogs">

	<? tab_bar_default("#blogs") ?>
	<div data-role="content" style="text-align:center;">
		<br><br>
		<select name="flip-1" id="flip-1" data-role="slider" onchange="$('#themes_ul, #phases_ul').toggle();">
			<option value="topic"><?= _("By topic") ?></option>
			<option value="phase"><?= _("By phase") ?></option>
		</select> 
		<br><br>
		<div id="themes_ul">
		<ul data-role="listview" data-inset="true" data-filter="true">
			<li data-role="list-divider"><?= _('Thèmes de projet') ?></li>
			<? foreach (Categories::$themes as $k=>$v): ?>
			<li>
				<a href="?action=Blog&id=<?= $v ?>" ><?= $v ?></a>
			</li>
			<? endforeach; ?>
		</ul>
		</div>
		<div id="phases_ul" style="display: none;">
		<ul data-role="listview" data-inset="true" data-filter="true">
			<li data-role="list-divider"><?= _('Phases du projet') ?></li>
			<? foreach (Categories::$phases as $k=>$v): ?>
			<li>
				<a href="?action=Blog&id=<?= $v ?>" ><?= $v ?></a>
			</li>
			<? endforeach; ?>
		</ul>
		</div>
		
		
		
		<br>
		<div data-role="collapsible" data-collapsed="true" data-mini="true" data-content-theme="c">
			<h3>Beta tests</h3>
			<ul data-role="listview" >
				<li data-role="list-divider"></li>
				<li>
					<a href="?action=Blog&id=myEurope"  rel="external" class="mymed-huge-button"><?= _('Bugs et problèmes rencontrés') ?></a>
				</li>
				<li>
					<a href="?action=Blog&id=Ameliorations proposees"  rel="external" class="mymed-huge-button"><?= _('Améliorations proposées') ?></a>
				</li>
				<li>
					<a href="?action=Blog&id=Discussion libre"  rel="external" class="mymed-huge-button"><?= _('Discussion libre') ?></a>
				</li>
			</ul>
		</div>
		
	</div>
</div>

<div data-role="page" id="search">

	<? tabs_simple(array('Search')); ?>
	<div data-role="content">
		<br>
		<form action="" id="searchForm">
			<input type="hidden" name="action" value="Search" />
			<div data-role="popup" id="helpPopup" class="ui-content"
				data-overlay-theme="e" data-theme="d">
				<a href="#" data-rel="back" data-role="button" data-theme="d"
					data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
				<ul data-role="listview" data-theme="d">
					<li>Si vous laissez tous les champs <b>vides</b>, vous
					obtenez toutes les offres publiées à ce jour</li>
					<li>Lorsque vous
					laissez une categorie <b>vide</b>, elle n'est pas prise en compte dans la recherche.</li>
					<li>Lorsque vous cochez/ remplissez plusieurs champs dans une catégorie, les 
						résultats matcheront au moins un des critères.</li>
				</ul>
			</div>
			
			<div data-role="fieldcontain" id="tagsContainer">
				<label for="textinput1"><?= _('keywords') ?>:</label>
				<input id="textinput1" class="tagInput" name="k[]"
					placeholder="<?= _('separated by a space') ?>"
					list="keywords" style="width: 50%;min-width: 300px;margin-bottom: 5px;"/>
			</div>
			
			
			<div style="text-align: center;" data-role="controlgroup" data-type="horizontal">
				<input type="submit" id="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>" />
				<a href="#helpPopup" data-rel="popup" data-position-to="window"
					data-theme="e" data-role="button"
					data-icon="question-sign" data-iconpos="right"><?= _("Help") ?>
				</a> 
			</div>
			 
			<br>
			<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
				<div  data-role="collapsible" data-collapsed="false">
					<h3>
					<?= _('Offer Themes') ?>:
					</h3>
				 	<fieldset data-role="controlgroup">
						<? foreach (Categories::$themes as $k=>$v): ?>
							<input type="checkbox" name="t[]" value="<?= $k ?>"
								id="checkbox-<?= $k ?>" /> <label for="checkbox-<?= $k ?>"><?= $v ?>
							</label>
						<? endforeach; ?>
					</fieldset>
				</div>
				<div data-role="collapsible" data-collapsed="true">
					<h3>
					<?= _('Areas') ?>
						:
					</h3>
					<fieldset data-role="controlgroup">
						<div data-role="collapsible-set" data-mini="true">
							<div data-role="collapsible" data-collapsed="false">
								<h3>
								<?= _("France") ?>
								</h3>
								<? foreach (Categories::$places_fr as $k=>$v): ?>
								<input type="checkbox" name="pf[]" value="<?= $v ?>"
									id="checkbox-f<?= $k ?>" /> <label for="checkbox-f<?= $k ?>"><?= $v ?>
								</label>
								<? endforeach; ?>
							</div>
							<div data-role="collapsible" data-collapsed="true">
								<h3>
								<?= _("Italy") ?>
								</h3>
								<? foreach (Categories::$places_it as $k=>$v): ?>
								<input type="checkbox" name="pi[]" value="<?= $v ?>"
									id="checkbox-i<?= $k ?>" /> <label for="checkbox-i<?= $k ?>"><?= $v ?>
								</label>
								<? endforeach; ?>
							</div>
							<div data-role="collapsible" data-collapsed="true">
								<h3>
								<?= _("Other") ?>
								</h3>
								<? foreach (Categories::$places_ot as $k=>$v): ?>
								<input type="checkbox" name="po[]" value="<?= $v ?>"
									id="checkbox-o<?= $k ?>" /> <label for="checkbox-o<?= $k ?>"><?= $v ?>
								</label>
								<? endforeach; ?>
							</div>
						</div>
					</fieldset>
				</div>

				<div data-role="collapsible" data-collapsed="true">
					<h3>
					<?= _('Category of searched partners') ?>
					</h3>
					<fieldset data-role="controlgroup">
						<? foreach (Categories::$roles as $k=>$v): ?>
						<input type="checkbox" name="r[]" value="<?= $k ?>"
							id="checkbox-<?= $k ?>" /> <label for="checkbox-<?= $k ?>"><?= $v ?>
						</label>
						<? endforeach; ?>
					</fieldset>

					<div data-role="fieldcontain">
						<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
						<select name="c" id="call">
						<? foreach (Categories::$calls as $k=>$v): ?>
							<option value="<?= $k ?>">
							<?= $v ?>
							</option>
						<? endforeach; ?>
						</select>
					</div>

				
				</div>
			</div>

		</form>
		
		<datalist id="keywords">
		<? foreach (Categories::$keywords as $v): ?>
			<option value="<?= _($v) ?>"/>
		<? endforeach; ?>
		</datalist>
		  
	</div>
</div>

<div data-role="page" id="post">
	
	<? tabs_simple(array('Insert')) ?>
	<div data-role="content">
		<form action="./" method="post" id="publishForm" data-ajax="false">

			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="method" value="create" /> <input
				type="hidden" name="r"
				value="<?= $_SESSION['myEurope']->details['role'] ?>" />

			<div data-role="fieldcontain">
				<label for="textinputp3" class="postTitle"><b><?= _('Title') ?> </b>
				</label> <input id="textinputp3" class="postTitle"
					data-inline="true" name="title"
					placeholder="<?= _("partnership or project name") ?>" value=''
					type="text" />
			</div>

			<div data-role="collapsible" data-collapsed="false" data-theme="b"
				data-content-theme="d" data-mini="true">
				<h3>Options</h3>

				<div data-role="collapsible-set" data-theme="c"
					data-content-theme="d">
					<div data-role="collapsible" data-collapsed="false">
						<h3>
						<?= _('Themes') ?>
							:
						</h3>
						<fieldset data-role="controlgroup">
						<? foreach (Categories::$themes as $k=>$v): ?>
							<input type="checkbox" name="t[]" value="<?= $k ?>"
								id="checkbox-<?= $k ?>" /> <label for="checkbox-<?= $k ?>"><?= $v ?>
							</label>
							<? endforeach; ?>
						</fieldset>
					</div>
					<div data-role="collapsible" data-collapsed="true">
						<h3>
						<?= _('Areas') ?>
							:
						</h3>
						<fieldset data-role="controlgroup">
							<div data-role="collapsible-set" data-mini="true">
								<div data-role="collapsible" data-collapsed="false">
									<h3>
									<?= _("France") ?>
									</h3>
									<? foreach (Categories::$places_fr as $k=>$v): ?>
									<input type="checkbox" name="pf[]" value="<?= $v ?>"
										id="checkbox-f<?= $k ?>" /> <label for="checkbox-f<?= $k ?>"><?= $v ?>
									</label>
									<? endforeach; ?>
								</div>
								<div data-role="collapsible" data-collapsed="true">
									<h3>
									<?= _("Italy") ?>
									</h3>
									<? foreach (Categories::$places_it as $k=>$v): ?>
									<input type="checkbox" name="pi[]" value="<?= $v ?>"
										id="checkbox-i<?= $k ?>" /> <label for="checkbox-i<?= $k ?>"><?= $v ?>
									</label>
									<? endforeach; ?>
								</div>
								<div data-role="collapsible" data-collapsed="true">
									<h3>
									<?= _("Other") ?>
									</h3>
									<? foreach (Categories::$places_ot as $k=>$v): ?>
									<input type="checkbox" name="po[]" value="<?= $v ?>"
										id="checkbox-o<?= $k ?>" /> <label for="checkbox-o<?= $k ?>"><?= $v ?>
									</label>
									<? endforeach; ?>
								</div>
							</div>
						</fieldset>
					</div>
					<div data-role="collapsible" data-collapsed="true">
						<h3>
						<?= _('Other options') ?>
							:
						</h3>

						<div data-role="fieldcontain">
							<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
							<select name="c" id="call">
							<? foreach (Categories::$calls as $k=>$v): ?>
								<option value="<?= $k ?>">
								<?= $v ?>
								</option>
								<? endforeach; ?>
							</select>
						</div>

						<div data-role="fieldcontain" id="tagsContainer2">
							<label for="textinput1"><?= _('Keywords') ?>: </label>
 							 <input id="textinput1" class="tagInput"
								placeholder="<?= _('separated by a space') ?>"
								name="k[]" list="keywords" style="width: 50%;min-width: 300px;margin-bottom: 5px;"/>
						</div>

						<div data-role="fieldcontain">
							<label for="textinputp2"><?= _('Date of expiration') ?>: </label>
							<input id="textinputp2" name="date"
								placeholder="<?= _('date in format year-month-day') ?>" value=''
								type="date" />
						</div>
					</div>
				</div>
	
			</div>

			<textarea id="CLEeditor" name="text">
				<h1><?= _("Your partnership") ?></h1>  ...</textarea>

			<div style="text-align: center;">
				<input id="submit2" type="submit" class="ui-btn-active ui-state-persist"
					data-inline="true" data-icon="check" value="<?=_('Insert') ?>" />
			</div>
		</form>
	</div>
</div>​


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