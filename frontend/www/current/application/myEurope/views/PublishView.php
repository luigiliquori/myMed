<!-- STEP1: Select Category -->
<div data-role="page" id="post">
	
	<? print_header_bar(true, "publishHelpPopup"); ?>
	
	<div data-role="content">
	
		<form action="./" method="post" id="publishForm" data-ajax="false">
		
			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="method" value="create" /> <input
				type="hidden" name="r"
				value="<?= $_SESSION['myEurope']->details['role'] ?>" />
	
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3>Comment pubiler ?</h3>
				<p>Saisissez un <b>titre</b> pour votre projet, puis une liste de <b>mots-clés</b> séparés par un espace<br />
				Rédigez votre offre a l'aide de l'editeur en ligne de myEurope. N'hesitez pas à compléter votre fiche de projet dans les options (bouton "Option").
				</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Publier votre projet') ?> :</h3>
				
				<h3><?= _('Title') ?> : </h3>
				<input id="textinputp3" class="postTitle"
					data-inline="true" name="title"
					placeholder="<?= _("partnership or project name") ?>" value=''
					type="text" />
				
				<h3><?= _('Keywords') ?> : </h3>
	 			<input id="textinput1" placeholder="<?= _('separated by a space') ?>" name="k[]" list="keywords" />
				
				<h3><?= _('Texte libre') ?> :</h3>
				<div data-role="controlgroup" data-type="horizontal"> 
					<a href="#publishOptionPopup" data-rel="popup" data-role="button" data-inline="true" data-icon="gear" data-mini="true"><?= _("Option") ?></a>
					<a href="#publishExamplePopup" data-rel="popup" data-role="button" data-inline="true" data-icon="star" data-mini="true" data-theme="e" data-iconpos="right"><?= _("Example") ?></a>
				</div>
				<textarea id="CLEeditor" name="text"></textarea>
				<br />
			</div>
			
			<div style="text-align: center;">
				<input id="submit2" type="submit" data-inline="true" data-icon="check" data-theme="g" value="<?=_('Insert') ?>" />
			</div>
	
			<div data-role="popup" id="publishOptionPopup" data-theme="b"
				data-content-theme="d" data-mini="true" class="ui-content">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
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
					
					<h3>
					<?= _('Areas') ?>
						:
					</h3>
					<fieldset data-role="controlgroup">
						<div data-role="collapsible-set" data-mini="true">
							<div data-role="collapsible" data-collapsed="true">
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
	
	
	
					<div data-role="fieldcontain">
						<label for="textinputp2"><?= _('Date of expiration') ?>: </label>
						<input id="textinputp2" name="date"
							placeholder="<?= _('date in format year-month-day') ?>" value=''
							type="date" />
					</div>
	
			</div>
	
		</form>
	</div>
	
	<!-- --------------------- -->
	<!-- PUBLISH EXAMPLE POPUP -->
	<!-- --------------------- -->
	<div data-role="popup" id="publishExamplePopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _('Title') ?> : </h3>
		<input type="text" value="Création d'un centre d'art dans la commune d'Embrun" readonly="readonly"/>
		<h3><?= _('Keywords') ?> : </h3>
		<input type="text" value="Embrun art contemporain histoire culture jeunes Alcotra" readonly="readonly"/>
		<h3><?= _('Texte libre') ?> :</h3>
		<textarea rows="80" cols="80" readonly="readonly">J’aimerais redonner une attractivité à ma commune en y créant un centre d’art. En effet, alors que notre commune nous offre des lieux chargés d’histoire, nous ne disposons pas de lieu où nous pourrions promouvoir l’art contemporain, de façon à faire le lien entre les deux époques.

	- Mon but serait, en dehors de la création d’un centre d’art, de promouvoir la culture auprès des plus jeunes, de stimuler la population.
	- Je recherche un partenaire italien qui soit intéressé par ma proposition pour que nous puissions soumissionner un projet Alcotra et réaliser ainsi de véritables circuits culturels transfrontaliers.
		</textarea>
	</div>
	
	<!-- --------------------- -->
	<!-- PUBLISH HELP POPUP -->
	<!-- --------------------- -->
	<div data-role="popup" id="publishHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3>Noubliez pas :</h3>
		<p>de renseigner des champs présents dans <a href="#searchOptionPopup" data-rel="popup" data-role="button" data-inline="true" data-icon="gear" data-mini="true" data-theme="e"><?= _("Option") ?></a></p>
		<p>Plus les options sont remplies, plus un projet sera visible .</p>
		<p>Lorsque vous cochez/ remplissez plusieurs champs dans une catégorie, les 
		résultats correspondront à au moins un des critères coché .</p>
	</div>
	
</div>

